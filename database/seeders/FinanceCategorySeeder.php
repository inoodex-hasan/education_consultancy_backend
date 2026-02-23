<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinanceCategory;

class FinanceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Rent', 'type' => 'expense'],
            ['name' => 'Marketing', 'type' => 'expense'],
            ['name' => 'Salaries', 'type' => 'expense'],
            ['name' => 'Utilities', 'type' => 'expense'],
            ['name' => 'Office Supplies', 'type' => 'expense'],
            ['name' => 'Student Fees', 'type' => 'income'],
            ['name' => 'Miscellaneous', 'type' => 'both'],
        ];

        foreach ($categories as $category) {
            FinanceCategory::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
