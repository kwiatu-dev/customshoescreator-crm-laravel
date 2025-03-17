<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserEventType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserEvents>
 */
class UserEventsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_id = $this->random_user_id();
        $creator_id = fake()->boolean(50) ? $user_id : $this->random_admin_id();
        $start = fake()->dateTimeBetween('-2 years', '+6 months');
        $end = fake()->dateTimeBetween($start, (clone $start)->modify('+2 months'));

        return [
            'title' => fake()->text(50),
            'start' => $start,
            'end' => $end,
            'remarks' => $this->generate_remarks(),
            'created_by_user_id' => $creator_id,
            'user_id' => $user_id,
            'type_id' => $this->random_type_id(),
        ];
    }

    private function generate_remarks()
    {
        return fake()->boolean(10) ? fake()->text(150) : null;
    }

    private function random_user_id()
    {
        return User::all()->random()->id;
    }

    private function random_admin_id()
    {
        return User::where('is_admin', true)->get()->random()->id;
    }

    private function random_type_id() {
        return UserEventType::all()->random()->id;
    }
}
