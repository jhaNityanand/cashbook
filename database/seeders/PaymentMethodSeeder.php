<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('payment_methods')->insert([
            [
                'name' => 'Cash',
                'description' => 'Physical cash payment.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Online',
                'description' => 'Generic online payment (card, netbanking, etc.).',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Google Pay',
                'description' => 'Google Pay (UPI) payments.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Phone Pe',
                'description' => 'PhonePe payments (UPI/wallet).',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Paytm',
                'description' => 'Paytm wallet/bank transfers.',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

    }
}
