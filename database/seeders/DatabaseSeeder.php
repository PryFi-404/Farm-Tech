<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * ORDER MATTERS — respect foreign key dependencies.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,    // 1. Users first (farmers depend on users)
            CropSeeder::class,    // 2. Master crop list
            SchemeSeeder::class,  // 3. Government schemes
            FarmerSeeder::class,  // 4. Farmers + lands + crop histories + applications
            ShgSeeder::class,     // 5. SHG/FPG groups (need farmers to exist)
        ]);
    }
}
