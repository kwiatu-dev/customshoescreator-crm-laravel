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
        return [
            'title' => fake()->text(50),
            'remarks' => fake()->text(150),
            'price' => fake()->randomFloat(2, 10, 1000),
            'start' => fake()->date,
            'deadline' => fake()->date,
            'commission' => 65,
            'costs' => 30,
            'distribution' => json_encode(['1' => 50, '2' => 50]),
            'visualization' => fake()->randomFloat(2, 0, 100),
            'created_by_user_id' => $this->user(),
            'client_id' => $this->client(),
            'status_id' => $this->status(),
            'type_id' => $this->type(),
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
