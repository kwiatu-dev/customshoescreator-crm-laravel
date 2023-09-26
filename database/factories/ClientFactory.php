<?php

namespace Database\Factories;

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
            'street_nr' => fake('pl_PL')->buildingNumber(),
            'postcode' => fake('pl_PL')->postcode(),
            'city' => fake('pl_PL')->city(),
            'country' => 'Polska',
            'username' => fake('pl_PL')->userName(),
            'conversion_source' => null,
            'social_link' => fake('pl_PL')->url(),
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

    public function conversion()
    {
        $socialMediaNames = ['Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'Pinterest'];
    
        return $this->state(
            fn (array $attributes) => [
                'conversion_source' => $socialMediaNames[array_rand($socialMediaNames)]
            ]
        );
    }
}
