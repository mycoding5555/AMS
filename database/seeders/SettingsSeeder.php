<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            ['key' => 'app_name', 'value' => 'Apartment Management System'],
            ['key' => 'app_start_date', 'value' => null],
            ['key' => 'app_close_date', 'value' => null],
            ['key' => 'language', 'value' => 'en'],
            ['key' => 'theme', 'value' => 'light'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'timezone', 'value' => 'UTC'],
            ['key' => 'company_name', 'value' => ''],
            ['key' => 'company_address', 'value' => ''],
            ['key' => 'company_phone', 'value' => ''],
            ['key' => 'company_email', 'value' => ''],
            ['key' => 'tax_rate', 'value' => '0'],
            ['key' => 'maintenance_mode', 'value' => 'off'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
