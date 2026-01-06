<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Farmer;
use Illuminate\Support\Facades\Hash;

class FarmerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Farmer::create([
            'name' => 'Farmer 1',
            'email' => 'farmer@gmail.com',
            'password' => Hash::make('Password123!'),
            'gender' => 'male',
            'status' => 'active',
            'phone' => '1234567890',
            'birthday' => '1990-01-01',
            'created_by' => 1,
        ]);
    }
}
