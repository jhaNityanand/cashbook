<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('categories')->insert([
            [
                'name' => 'Food',
                'description' => 'Expenses related to meals, groceries and food supplies.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Movies, shows, recreation, subscriptions and leisure activities.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Purchase',
                'description' => 'Purchases of goods for business or stock/inventory.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Sales',
                'description' => 'Sales income categories (income from product/service sales).',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Salary',
                'description' => 'Payroll and salary expenses.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Utilities',
                'description' => 'Electricity, water, internet and other utilities.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
