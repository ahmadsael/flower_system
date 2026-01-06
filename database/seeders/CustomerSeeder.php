<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Customer 1',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'gender' => 'male',
            'status' => 'active',
            'phone' => '1234567890',
            'birthday' => '1990-01-01',
            'created_by' => 1,
        ]);
    }
}
