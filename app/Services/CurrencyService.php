<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $apiUrl = 'https://open.er-api.com/v6/latest/BDT';

    public function updateRates()
    {
        try {
            $response = Http::withoutVerifying()->get($this->apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];
                $updatedCount = 0;

                foreach ($rates as $code => $rate) {
                    $currency = Currency::where('code', $code)->first();
                    if ($currency) {
                        $currency->update([
                            'exchange_rate' => $rate,
                            'last_updated_at' => now(),
                        ]);
                        $updatedCount++;
                    }
                }

                return [
                    'success' => true,
                    'message' => "Successfully updated {$updatedCount} currencies.",
                ];
            }

            return [
                'success' => false,
                'message' => "Failed to fetch rates: " . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error("Currency Update Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred: " . $e->getMessage(),
            ];
        }
    }
}
