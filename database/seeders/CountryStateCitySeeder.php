<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountryStateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Country, State, City seeding logic can be added here in the future
        $now = Carbon::now();

        // --- Countries ---
        $countries = [
            ['name' => 'India', 'country_code' => 'IN', 'phone_code' => '+91'],
            ['name' => 'United States', 'country_code' => 'US', 'phone_code' => '+1'],
            ['name' => 'United Kingdom', 'country_code' => 'GB', 'phone_code' => '+44'],
        ];

        foreach ($countries as $country) {
            $countryIds[$country['country_code']] = DB::table('countries')->insertGetId(array_merge($country, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // --- States ---
        $states = [
            ['country_id' => $countryIds['IN'], 'name' => 'Gujarat', 'state_code' => 'GJ'],
            ['country_id' => $countryIds['IN'], 'name' => 'Maharashtra', 'state_code' => 'MH'],
            ['country_id' => $countryIds['US'], 'name' => 'California', 'state_code' => 'CA'],
            ['country_id' => $countryIds['US'], 'name' => 'Texas', 'state_code' => 'TX'],
        ];

        foreach ($states as $state) {
            $stateIds[$state['name']] = DB::table('states')->insertGetId(array_merge($state, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // --- Cities ---
        $cities = [
            ['state_id' => $stateIds['Gujarat'], 'name' => 'Surat'],
            ['state_id' => $stateIds['Gujarat'], 'name' => 'Ahmedabad'],
            ['state_id' => $stateIds['Maharashtra'], 'name' => 'Mumbai'],
            ['state_id' => $stateIds['Maharashtra'], 'name' => 'Pune'],
            ['state_id' => $stateIds['California'], 'name' => 'Los Angeles'],
            ['state_id' => $stateIds['California'], 'name' => 'San Francisco'],
            ['state_id' => $stateIds['Texas'], 'name' => 'Houston'],
            ['state_id' => $stateIds['Texas'], 'name' => 'Dallas'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert(array_merge($city, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
