<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'first_name' => fake('pl_PL')->firstName(),
            'last_name' => fake('pl_PL')->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'password' => 'password',
            'remember_token' => Str::random(10),
            'phone' => fake('pl_PL')->phoneNumber(),
            'street' => fake('pl_PL')->streetName(),
            'street_nr' => fake()->numberBetween(10, 100),
            'apartment_nr' => fake()->numberBetween(10, 100),
            'postcode' => fake('pl_PL')->postcode(),
            'city' => fake('pl_PL')->city(),
            'country' => 'Polska',
            'commission' => 65,
            'costs' => 30,
            'distribution' => ['1' => 50, '2' => 50]
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
