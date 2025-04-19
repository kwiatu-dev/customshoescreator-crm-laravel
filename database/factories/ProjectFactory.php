<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use App\Models\ProjectType;
use App\Models\ProjectStatus;
use DateTime;
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
        $createdAt = fake()->dateTimeBetween(date('Y') - 1 . '-01-01', '-2 months'); 
        $start = fake()->dateTimeBetween($createdAt, (clone $createdAt)->modify('+6 months'));
        $deadline = fake()->dateTimeBetween($start, (clone $start)->modify('+1 months')); 
        $temp = fake()->dateTimeBetween((clone $deadline)->modify('-14 days'), (clone $deadline)->modify('+14 days'));

        $now = new \DateTime();

        if ($start > $now) {
            $status_id = 1;
            $end = null;
        }
        else if ($start < $now && $deadline > $now) {
            $status_id = 2;
            $end = null;
        }
        elseif ($deadline < (clone $now)->modify('-2 month')) {
            $status_id = fake()->boolean(90) ? 3 : 2;
            $end = $status_id === 3 ? fake()->dateTimeBetween((clone $deadline)->modify('-14 days'), (clone $deadline)->modify('+14 days')) : null;
        }
        elseif ($deadline < (clone $now)->modify('-1 month')) {
            $status_id = fake()->boolean(70) ? 3 : 2;
            $end = $status_id === 3 ? fake()->dateTimeBetween((clone $deadline)->modify('-14 days'), (clone $deadline)->modify('+14 days')) : null;
        } 
        elseif ($deadline < $now && (clone $now)->modify('-1 month') < $deadline) {
            $status_id = fake()->boolean(10) ? 3 : 2;
            $end = $status_id === 3 ? fake()->dateTimeBetween($deadline, $now) : null;
        } 
        else {
            $status_id = 1;
            $end = null;
        }

        return [
            'title' => fake()->text(50),
            'remarks' => fake()->text(150),
            'price' => fake()->randomFloat(2, 10, 1000),
            'start' => $start,
            'deadline' => $deadline,
            'end' => $end,
            'commission' => 65,
            'costs' => 30,
            'distribution' => ['1' => 50, '2' => 50],
            'visualization' => fake()->randomFloat(2, 0, 100),
            'created_by_user_id' => $this->user(),
            'client_id' => $this->client(),
            'status_id' => $status_id,
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
