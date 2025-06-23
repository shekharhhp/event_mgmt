<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add these two lines:
        $this->call([
            RolesTableSeeder::class,
            TagSeeder::class,
        ]);
    }
}
