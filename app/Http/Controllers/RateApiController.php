<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RateApiController extends Controller
{
    /**
     * Fetch live and historical rate data via C# tool.
     */
    public function getRates($from, $to, $days = 7)
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        $days = (int)$days;

        try {
            $path = base_path('tools/CurrencyFetcher');
            $command = "dotnet run --project " . escapeshellarg($path) . " " . escapeshellarg($from) . " " . escapeshellarg($to) . " " . escapeshellarg($days);
            
            $output = shell_exec($command);
            
            if ($output) {
                // Extract JSON if there's build noise (e.g. from dotnet run)
                if (preg_match('/\{.*\}/s', $output, $matches)) {
                    $output = $matches[0];
                }
                
                $data = json_decode($output, true);
                if (isset($data['rates'])) {
                    return response()->json($data);
                }
                
                return response()->json(['error' => 'Invalid data from C# tool', 'details' => $output], 500);
            }
            
            return response()->json(['error' => 'C# tool returned no output'], 500);
            
        } catch (\Exception $e) {
            Log::error("API Rate Fetch Failed: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
