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
        $created_at = fake()->dateTimeBetween(date('Y') - 1 . '-01-01', '-2 months');
        $date = fake()->dateTimeBetween($created_at, (clone $created_at)->modify('+2 months'));
        $amount = fake()->randomFloat(2, 10, 1000);
        $interest_rate = fake()->boolean(90) ? 0 : fake()->numberBetween(0, 20);
        $status_id = $this->random_status_id();
        $total_repayment = 0;
        $total_amount = round($amount + ($amount * ($interest_rate / 100)), 2);

        if ($status_id == 2) {
            $total_repayment = $total_amount;
        }
        else {
            $total_repayment = fake()->boolean() ? 0 : fake()->randomFloat(2, round($total_amount * 0.1, 2), $total_amount);
        }

        return [
            'title' => fake()->text(50),
            'amount' => $amount,
            'date' => $date,
            'interest_rate' => $interest_rate,
            'total_repayment' => $total_repayment,
            'remarks' => $this->generate_remarks(),
            'user_id' => $this->random_user_id(),
            'status_id' => $status_id,
            'created_by_user_id' => $this->random_admin_id(),
            'created_at' => $created_at,
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
