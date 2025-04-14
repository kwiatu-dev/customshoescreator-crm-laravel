<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use App\Models\ProjectType;
use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-1 year', 'now'); 
        $start = fake()->dateTimeBetween($createdAt, 'now'); 
        $deadline = fake()->dateTimeBetween($start, (clone $start)->modify('+2 months')); 
        $status = $this->status();
        $end = $status->id === 3 ? fake()->dateTimeBetween($start, $deadline) : null;

        return [
            'title' => fake()->text(50),
            'remarks' => fake()->text(150),
            'price' => fake()->randomFloat(2, 10, 1000),
            'start' => $start,
            'deadline' => $deadline,
            'end' => $end,
            'commission' => 65,
            'costs' => 30,
            'distribution' => json_encode(['1' => 50, '2' => 50]),
            'visualization' => fake()->randomFloat(2, 0, 100),
            'created_by_user_id' => $this->user(),
            'client_id' => $this->client(),
            'status_id' => $status->id,
            'type_id' => $this->type(),
            'created_at' => $createdAt,
        ];
    }

    public function user()
    {
        return User::all()->random();
    }

    public function client()
    {
        return Client::all()->random();
    }

    public function status()
    {
        return ProjectStatus::all()->random();
    }

    public function type()
    {
        return ProjectType::all()->random();
    }
}
