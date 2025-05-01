<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superadmin@123'),
            'role' => 'superadmin',
        ]);

        // Admins
        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('admin1@123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('admin2@123'),
            'role' => 'admin',
        ]);

        // Regular Users
        User::create([
            'name' => 'User One',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('user1@123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('user2@123'),
            'role' => 'user',
        ]);

    

    }
}
