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
        // User::factory(10)->create();

        // Create an Admin user
        User::factory()->create([
            'username' => 'admin',
            'phone' => '0999888777',
            'password' => 'admin123',
            'role' => 'ADMIN',
        ]);

        // Create an Artist user
        User::factory()->create([
            'username' => 'artist',
            'phone' => '0111222333',
            'password' => 'artist123',
            'role' => 'ARTIST',
        ]);

        // Create a regular User
        User::factory()->create([
            'username' => 'testuser',
            'phone' => '0123456789',
            'password' => '123456',
            'role' => 'USER',
        ]);
    }
}
