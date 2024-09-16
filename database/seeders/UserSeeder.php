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
            'name' => 'manager1',
            'email' => 'manager1@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);
        User::create([
            'name' => 'manager2',
            'email' => 'manager2@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);

        User::create([
            'name' => 'developer1',
            'email' => 'developer1@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);
        User::create([
            'name' => 'developer2',
            'email' => 'developer2@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);

        User::create([
            'name' => 'tester1',
            'email' => 'tester1@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);
        User::create([
            'name' => 'tester2',
            'email' => 'tester2@gmail.com',
            'password' => '12345678',
            'type' => 'user',
        ]);
    }
}
