<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'Super Admin',
            'status' => 'active',
            'type'=> 'admin'
        ]);
    }
}
