using System;
using System.Net.Http;
using System.Threading.Tasks;
using System.Text.Json;
using System.Collections.Generic;
using System.Linq;

namespace CurrencyFetcher
{
    class Program
    {
        static async Task Main(string[] args)
        {
            if (args.Length < 2)
            {
                Console.WriteLine("Error: Missing parameters. Usage: CurrencyFetcher <from> <to> [days]");
                return;
            }

            string from = args[0].ToUpper();
            string to = args[1].ToUpper();
            int days = args.Length > 2 && int.TryParse(args[2], out int d) ? d : 0;

            try
            {
                using HttpClient client = new HttpClient();
                client.DefaultRequestHeaders.Add("User-Agent", "CurrencyFetcher/1.0");

                if (days <= 1)
                {
                    // For latest rates, open.er-api.com is excellent and supports 160+ currencies
                    string url = $"https://open.er-api.com/v6/latest/{from}";
                    var response = await client.GetStringAsync(url);
                    
                    // Simple validation
                    if (response.Contains("\"result\":\"success\"")) {
                        Console.WriteLine(response);
                    } else {
                        // Fallback for latest if open.er-api fails
                        string fallbackUrl = $"https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{from.ToLower()}.json";
                        var fbResponse = await client.GetStringAsync(fallbackUrl);
                        
                        using var doc = JsonDocument.Parse(fbResponse);
                        if (doc.RootElement.TryGetProperty(from.ToLower(), out var rates)) {
                            var normalized = new { 
                                result = "success", 
                                base_code = from, 
                                rates = rates.EnumerateObject().ToDictionary(x => x.Name.ToUpper(), x => x.Value.GetDouble()) 
                            };
                            Console.WriteLine(JsonSerializer.Serialize(normalized));
                        } else {
                            Console.WriteLine(fbResponse); // Fallback to raw if logic fails
                        }
                    }
                }
                else
                {
                    // Historical Range
                    string end = DateTime.Now.ToString("yyyy-MM-dd");
                    string start = DateTime.Now.AddDays(-days).ToString("yyyy-MM-dd");
                    string frankfurterUrl = $"https://api.frankfurter.app/{start}..{end}?from={from}&to={to}";
                    
                    try {
                        var response = await client.GetStringAsync(frankfurterUrl);
                        Console.WriteLine(response);
                    } catch {
                        // FALLBACK: Frankfurter failed (likely currency not supported)
                        // Use fawazahmed0/currency-api which supports 150+ currencies
                        
                        var ratesDict = new Dictionary<string, object>();
                        
                        // Sample 6 points to create a decent graph
                        var tasks = new List<Task<(string Date, string Json)>>();
                        for (int i = 0; i <= 5; i++) {
                            int offset = (days * (5 - i)) / 5; // Fixed order to be chronological
                            string dateStr = DateTime.Now.AddDays(-offset).ToString("yyyy-MM-dd");
                            string url = $"https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@{dateStr}/v1/currencies/{from.ToLower()}.json";
                            tasks.Add(FetchDate(client, dateStr, url));
                        }
                        
                        var fetched = await Task.WhenAll(tasks);
                        foreach (var item in fetched.OrderBy(x => x.Date)) {
                            if (item.Json != null) {
                                using var doc = JsonDocument.Parse(item.Json);
                                if (doc.RootElement.TryGetProperty(from.ToLower(), out var rates)) {
                                    ratesDict[item.Date] = rates.EnumerateObject().ToDictionary(x => x.Name.ToUpper(), x => x.Value.GetDouble());
                                }
                            }
                        }
                        
                        if (ratesDict.Count > 0) {
                            var output = new { base_code = from, rates = ratesDict };
                            Console.WriteLine(JsonSerializer.Serialize(output));
                        } else {
                             Console.WriteLine($"{{\"error\": \"Historical data not supported for {from}/{to} via any provider.\"}}");
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"{{\"error\": \"{ex.Message}\"}}");
            }
        }

        static async Task<(string Date, string Json)> FetchDate(HttpClient client, string date, string url) {
            try {
                var res = await client.GetStringAsync(url);
                return (date, res);
            } catch {
                return (date, null);
            }
        }
    }
}
