<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Show;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition()
    {
        return [
            'show_id' => Show::factory(), // Automatically create a related Show
            'user_id' => User::factory(), // Automatically create a related User
            'rating'  => $this->faker->numberBetween(1, 10), // Random rating between 1 and 10
        ];
    }
}
