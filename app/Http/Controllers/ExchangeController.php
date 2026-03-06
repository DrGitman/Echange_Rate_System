<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    /**
     * Display the calculator page.
     */
    public function index()
    {
        $countries = json_decode(File::get(resource_path('data/countries.json')), true);

        return view('calculator', [
            'countries'    => $countries,
            'amount'       => 0,
            'from_currency'=> 'USD',
            'to_currency'  => 'EUR',
            'exchangeRate' => 0,
            'result'       => 0
        ]);
    }

    /**
     * Handle the currency conversion logic.
     */
    public function calculate(Request $request)
    {
        $countries = json_decode(File::get(resource_path('data/countries.json')), true);

        $request->validate([
            'amount'        => 'required|numeric|min:0',
            'from_currency' => 'required|string|size:3',
            'to_currency'   => 'required|string|size:3',
        ]);

        $amount = $request->amount;
        $from = strtoupper($request->from_currency);
        $to = strtoupper($request->to_currency);

        // Call the C# Tool for Live Rates
        $rate = 1.0;
        
        if ($from !== $to) {
            try {
                // Execute C# project via dotnet run
                $path = base_path('tools/CurrencyFetcher');
                // Use shell_exec to get output. We use escapeshellarg for security.
                $command = "dotnet run --project " . escapeshellarg($path) . " " . escapeshellarg($from) . " " . escapeshellarg($to);
                
                $output = shell_exec($command);
                
                if ($output) {
                    // Extract JSON if there's build noise (e.g. from dotnet run)
                    if (preg_match('/\{.*\}/s', $output, $matches)) {
                        $output = $matches[0];
                    }
                    
                    $data = json_decode($output, true);
                    if (isset($data['rates'][$to])) {
                        $rate = (float)$data['rates'][$to];
                    } else if (isset($data['error'])) {
                        Log::warning("C# CurrencyFetcher error: " . $data['error']);
                        $rate = 0.92;
                    } else {
                        Log::warning("C# CurrencyFetcher invalid format: " . $output);
                        $rate = 0.92;
                    }
                } else {
                    Log::warning("C# CurrencyFetcher returned no output");
                    $rate = 0.92;
                }
            } catch (\Exception $e) {
                Log::error("Failed to run C# CurrencyFetcher: " . $e->getMessage());
                $rate = 0.92;
            }
        }
        
        $result = $amount * $rate;

        return view('calculator', [
            'countries'     => $countries,
            'amount'        => $amount,
            'from_currency' => $from,
            'to_currency'   => $to,
            'exchangeRate'  => $rate,
            'result'        => number_format($result, 2)
        ]);
    }
}