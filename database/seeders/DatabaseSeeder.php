<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        \App\Models\Listing::factory(10)->create([
            'by_user_id' => 1,
        ]);

        
        \App\Models\Listing::factory(10)->create([
            'by_user_id' => 2,
        ]);

        \App\Models\User::factory(10)->create();

        \App\Models\Client::factory(20)->create();

        \App\Models\Expenses::factory(10)->create();

        \App\Models\Project::factory(100)->create();

        $projects = \App\Models\Project::where('status_id', 3)->get();

        foreach ($projects as $project) {
            $endDate = new \DateTime($project->end);

            \App\Models\Income::factory()->create([
                'project_id' => $project->id,
                'costs' => $project->costs,
                'price' => $project->price,
                'distribution' => $project->distribution,
                'date' => fake()->dateTimeBetween($project->start, $endDate->modify('+1 month')),
                'created_at' => fake()->dateTimeBetween($project->start, $project->end),
            ]);
        }

        \App\Models\Income::factory(10)->create();

        \App\Models\Investment::factory(40)->create();

        $investments = \App\Models\Investment::all();

        foreach ($investments as $investment) {
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

        \App\Models\UserEvents::factory(30)->create();
    }
}
