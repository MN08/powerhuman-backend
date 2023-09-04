<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $gender = $this->faker->randomElement(['pria', 'wanita']);
        return [
            'name' => $this->faker->name($gender),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $gender,
            'age' =>  $this->faker->numberBetween(18, 60),
            'phone' => $this->faker->phoneNumber(),
            'photo' => $this->faker->imageUrl($category = 'person'),
            'team_id' => $this->faker->numberBetween(1, 9),
            'role_id' => $this->faker->numberBetween(1, 6),
            // 'is_verified' => $this->faker->numberBetween(0, 1),
            // 'verified_at' => $this->faker->numberBetween(0, 1),
        ];
    }
}
