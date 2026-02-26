<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->lastName(),
            'student_number' => $studentNumber = Str::random(14),
            'birth_date' => $this->faker->date('Y-m-d'),
            'program' => $this->faker->randomElement(['Software Engineering', 'Marketing', 'Mechanical Engineering', 'Psychology', 'Medicine']),
            'year_graduated' => $this->faker->year(),
            'date_time_created' => now(),
            'date_time_updated' => null,
        ];
    }
}
