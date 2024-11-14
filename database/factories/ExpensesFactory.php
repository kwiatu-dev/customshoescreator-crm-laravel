<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenses>
 */
class ExpensesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(50),
            'date' => fake()->date(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'shop_name' => fake()->company,
            'file' => fake()->word . '.pdf'
        ];
    }
}
