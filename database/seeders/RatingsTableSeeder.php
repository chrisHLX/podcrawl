<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating; // Import the Rating model
use App\Models\Show;   // Import the Show model
use App\Models\User;   // Import the User model

class RatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some ratings
        Rating::factory()->count(3)->create(); // This will create 3 random ratings
    }
}
