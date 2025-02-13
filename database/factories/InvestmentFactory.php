<?php

namespace Database\Factories;

use App\Models\InvestmentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 10, 1000);
        $interest_rate = fake()->boolean(90) ? 0 : fake()->numberBetween(0, 20);
        $status_id = $this->random_status_id();
        $total_repayment = 0;

        if ($status_id == 2) {
            $total_repayment = $amount + ($amount * ($interest_rate / 100));
        }
        else {
            $total_repayment = fake()->boolean(90) ? 0 : fake()->randomFloat(2, 0, $amount + ($amount * ($interest_rate / 100)));
        }

        return [
            'title' => fake()->text(50),
            'amount' => $amount,
            'date' => fake()->date(),
            'interest_rate' => $interest_rate,
            'total_repayment' => $total_repayment,
            'remarks' => $this->generate_remarks(),
            'user_id' => $this->random_user_id(),
            'status_id' => $status_id,
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

    private function random_user_id()
    {
        return User::all()->random()->id;
    }

    private function random_status_id()
    {
        return InvestmentStatus::all()->random()->id;
    }
}
