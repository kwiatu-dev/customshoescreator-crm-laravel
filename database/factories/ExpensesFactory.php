<?php

namespace Database\Factories;

use App\Models\User;
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
            'file' => fake()->word . '.pdf',
            'created_by_user_id' => $this->random_user_id()
        ];
    }

    public function random_user_id()
    {
        return User::all()->random();
    }
}
