<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@farmtech.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // Create Field Officers
        User::create([
            'name'              => 'Rajesh Kumar',
            'email'             => 'officer1@farmtech.com',
            'password'          => Hash::make('password'),
            'role'              => 'officer',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Priya Sharma',
            'email'             => 'officer2@farmtech.com',
            'password'          => Hash::make('password'),
            'role'              => 'officer',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // Create Farmer Users (10 farmers)
        $farmers = [
            ['name' => 'Ramesh Patel',    'email' => 'ramesh@farmtech.com'],
            ['name' => 'Suresh Yadav',    'email' => 'suresh@farmtech.com'],
            ['name' => 'Anita Devi',      'email' => 'anita@farmtech.com'],
            ['name' => 'Mohan Singh',     'email' => 'mohan@farmtech.com'],
            ['name' => 'Kavita Kumari',   'email' => 'kavita@farmtech.com'],
            ['name' => 'Birendra Sahu',   'email' => 'birendra@farmtech.com'],
            ['name' => 'Lalita Bai',      'email' => 'lalita@farmtech.com'],
            ['name' => 'Dinesh Verma',    'email' => 'dinesh@farmtech.com'],
            ['name' => 'Savitri Devi',    'email' => 'savitri@farmtech.com'],
            ['name' => 'Raju Chauhan',    'email' => 'raju@farmtech.com'],
        ];

        foreach ($farmers as $farmer) {
            User::create([
                'name'              => $farmer['name'],
                'email'             => $farmer['email'],
                'password'          => Hash::make('password'),
                'role'              => 'farmer',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
