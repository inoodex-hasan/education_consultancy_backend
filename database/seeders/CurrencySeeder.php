<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'BDT', 'symbol' => '৳', 'is_active' => true],
            ['code' => 'GBP', 'symbol' => '£', 'is_active' => true],
            ['code' => 'USD', 'symbol' => '$', 'is_active' => true],
            ['code' => 'EUR', 'symbol' => '€', 'is_active' => true],
            ['code' => 'AUD', 'symbol' => 'A$', 'is_active' => true],
            ['code' => 'CAD', 'symbol' => 'C$', 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
