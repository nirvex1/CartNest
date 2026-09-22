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
        // Create an admin user with a custom password
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'poudelnirjal01@gmail.com',
            'gender' => 'male',
            'is_admin' => true,
            'password' => bcrypt('44444444'), // Set your desired password here
            'phone' => '1234567890',
        ]);

        // Create a regular user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'gender' => 'female',
            'is_admin' => false,
        ]);

        // Seed categories and products
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // Check if the admin user was created
        if ($adminUser) {
            echo "Admin user created successfully.\n";
        } else {
            echo "Failed to create admin user.\n";
        }
    }
}
