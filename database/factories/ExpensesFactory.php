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
        $created_at = fake()->dateTimeBetween(date('Y') - 1 . '-01-01', '-1 days');

        return [
            'title' => fake()->text(50),
            'date' => $created_at,
            'price' => fake()->randomFloat(2, 10, 1000),
            'shop_name' => fake()->company,
            'file' => fake()->word . '.pdf',
            'created_by_user_id' => $this->random_user_id(),
            'created_at' => $created_at,
        ];
    }

    public function random_user_id()
    {
        return User::all()->random();
    }
}
