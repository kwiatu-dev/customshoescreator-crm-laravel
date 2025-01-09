<?php

namespace Database\Factories;

use App\Models\ConversionSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
            'email' => fake('pl_PL')->safeEmail(),
            'email_verified_at' => null, 
            'phone' => fake('pl_PL')->phoneNumber(),
            'street' => fake('pl_PL')->streetName(),
            'street_nr' => fake()->numberBetween(10, 100),
            'apartment_nr' => fake()->numberBetween(10, 100),
            'postcode' => fake('pl_PL')->postcode(),
            'city' => fake('pl_PL')->city(),
            'country' => 'Polska',
            'username' => fake('pl_PL')->userName(),
            'conversion_source_id' => $this->random_conversion_source(),
            'social_link' => fake('pl_PL')->url(),
            'created_by_user_id' => $this->random_user_id()
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

    public function random_conversion_source()
    {
        return ConversionSource::all()->random();
    }

    public function random_user_id()
    {
        return User::all()->random();
    }
}
