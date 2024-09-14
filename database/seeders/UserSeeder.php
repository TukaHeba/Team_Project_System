<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'type' => 'admin',
        ]);

        User::create([
            'name' => 'manager',
            'email' => 'manager@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);

        User::create([
            'name' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);

        User::create([
            'name' => 'tester',
            'email' => 'tester@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);
    }
}
