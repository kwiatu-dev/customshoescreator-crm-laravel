<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvestmentRepayment>
 */
class InvestmentRepaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'remarks' => $this->generate_remarks(),
            'created_by_user_id' => $this->random_admin_id()
        ];
    }

    private function generate_remarks()
    {
        return fake()->boolean(10) ? fake()->text(150) : null;
    }

    private function random_admin_id()
    {
        return User::where('is_admin', true)->get()->random()->id;
    }
}
