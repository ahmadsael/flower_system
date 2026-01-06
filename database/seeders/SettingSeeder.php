<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Setting::create([
            'key' => 'sidebar_logo',
            'value' => 'sloopify-logo.svg',
            'description' => 'The name of the application',
            'status' => 'active',
        ]);
    }
}
