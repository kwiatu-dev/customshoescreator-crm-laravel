<?php

namespace Database\Factories;

use App\Models\IncomeStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status_id = $this->random_status_id();
        $created_at = fake()->dateTimeBetween(date('Y') - 1 . '-01-01', '-1 days');

        return [
            'title' => fake()->text(50),
            'date' => $status_id == 2 ? $created_at : null,
            'price' => fake()->randomFloat(2, 10, 1000),
            'status_id' => $status_id,
            'remarks' => $this->generate_remarks(),
            'project_id' => null,
            'created_by_user_id' => $this->random_admin_id(),
            'costs' => 50,
            'distribution' => json_encode(['1' => 50, '2' => 50]),
            'commission' => null,
            'created_at' => $created_at,
        ];
    }

    public function random_status_id()
    {
        return IncomeStatus::all()->random()->id;
    }

    public function random_user_id()
    {
        return User::all()->random()->id;
    }

    public function random_admin_id()
    {
        return User::query()->where('is_admin', 1)->inRandomOrder()->first()->id;
    }

    private function generate_remarks()
    {
        return fake()->boolean(10) ? fake()->text(150) : null;
    }
}
