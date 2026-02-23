<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update currency exchange rates from the API';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\CurrencyService $currencyService)
    {
        $this->info('Updating currency rates...');
        $result = $currencyService->updateRates();

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }
    }
}
