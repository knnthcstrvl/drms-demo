<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement(['Admin', 'User']),
            'access_level' => implode(',', $this->faker->randomElements(['View', 'Download', 'Print'], rand(1, 3))),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'profile_photo_path' => null,
            'remember_token' => Str::random(10),
            'date_time_created' => now(),
            'date_time_updated' => null,
        ];
    }
}
