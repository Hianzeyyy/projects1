<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Sample Student Account
        \App\Models\User::updateOrCreate(
            ['email' => 'user@psu.edu.ph'],
            [
                'name' => 'Student',
                'password' => bcrypt('student123'),
                'role' => 'student',
                'alias' => 'Hidden-Falcon-92',
                'email_verified_at' => now(),
            ]
        );

        // Sample Admin Account
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@psu.edu.ph'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'alias' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
