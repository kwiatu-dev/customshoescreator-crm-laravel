<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use DateTime;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->verified()->create([
            'first_name' => 'Filip',
            'last_name' => 'Kwiatkowski',
            'email' => 'filip@example.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->verified()->create([
            'first_name' => 'Aleksandra',
            'last_name' => 'Owidzka',
            'email' => 'aleksandra@example.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->verified()->create([
            'first_name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        \App\Models\Client::factory()->create([
            'first_name' => 'Wojciech',
            'last_name' => 'Konieczny',
            'email' => 'wkonieczny@example.com',
        ]);

        \App\Models\User::factory(5)->create();
        \App\Models\Client::factory(40)->create();
        \App\Models\Expenses::factory(20)->create();
        \App\Models\Project::factory(200)->create();
        \App\Models\Income::factory(10)->create();
        \App\Models\Investment::factory(40)->create();
        \App\Models\UserEvents::factory(30)->create();

        foreach (\App\Models\Project::where('status_id', 3)->get() as $project) {
            $status_id = fake()->boolean(90) ? 2 : 1;
            $project_start = new \DateTime($project->start);
            $project_deadline = new \DateTime($project->deadline);
            $project_end = new \DateTime($project->end);
            $date = null;

            $now = new \DateTime();
            $oneMonthAgo = (clone $now)->modify('-1 month');
        
            if ($project_end < $oneMonthAgo) {
                $date = fake()->dateTimeBetween($project_start, $project_end->modify('+1 month'));
            } else {
                $date = fake()->dateTimeBetween($project_end, 'now');
            }

            \App\Models\Income::factory()->create([
                'project_id' => $project->id,
                'costs' => $project->costs,
                'distribution' => $project->distribution,
                'commission' => $project->commission,
                'price' => $project->price,
                'date' => $status_id === 2 ? $date : null,
                'created_at' => fake()->dateTimeBetween($project->start, $project->deadline),
                'created_by_user_id' => null,
                'status_id' => $status_id
            ]);
        }

        foreach (\App\Models\Project::where('status_id', 2)->get() as $project) {
            $status_id = fake()->boolean(10) ? 2 : 1;
            $project_start = $project->start;
            $project_deadline = $project->deadline;

            if (new DateTime($project_deadline) < now()) {
                $created_at = fake()->dateTimeBetween($project_start, $project_deadline);
            }
            else {
                $created_at = fake()->dateTimeBetween($project_start, 'now');
            }
            

            \App\Models\Income::factory()->create([
                'project_id' => $project->id,
                'costs' => $project->costs,
                'distribution' => $project->distribution,
                'commission' => $project->commission,
                'price' => $project->price,
                'date' => $status_id === 2 ? fake()->dateTimeBetween($created_at, 'now') : null,
                'created_at' => $created_at,
                'created_by_user_id' => null,
                'status_id' => $status_id,
            ]);
        }

        foreach (\App\Models\Investment::all() as $investment) {
            if ($investment->total_repayment <= 0) {
                continue;
            }

            $investment_date = $investment->date;
            $investment_id = $investment->id;
            $remaining_amount = $investment->total_repayment;
            $repayment_amount = 0;

            while ($remaining_amount > 0) {
                $delta = fake()->boolean(80) ? 1 : fake()->randomFloat(1, 0.2, 0.6);
                $repayment_amount = fake()->randomFloat(2, 1, $remaining_amount * $delta);

                if ($remaining_amount <= $investment->total_repayment * 0.2) {
                    $repayment_amount = $remaining_amount;
                }

                if ($remaining_amount - $repayment_amount < 0) {
                    $repayment_amount = $remaining_amount;
                }

                \App\Models\InvestmentRepayment::factory()->create([
                    'repayment' => $repayment_amount,
                    'date' => fake()->dateTimeBetween($investment_date, 'now'), 
                    'investment_id' => $investment_id,
                ]);

                $remaining_amount -= $repayment_amount;
            }
        }
    }
}
