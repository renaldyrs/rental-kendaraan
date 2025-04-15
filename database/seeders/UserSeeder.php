<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@rental.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'can_verify' => true
    ]);
    
    \App\Models\User::create([
        'name' => 'Staff Verifikasi',
        'email' => 'verifikator@rental.com',
        'password' => bcrypt('password'),
        'role' => 'staff',
        'can_verify' => true
    ]);
    
    \App\Models\User::create([
        'name' => 'Staff Biasa',
        'email' => 'staff@rental.com',
        'password' => bcrypt('password'),
        'role' => 'staff',
        'can_verify' => false
    ]);
}
}
