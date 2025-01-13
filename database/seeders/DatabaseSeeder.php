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
            \App\Models\Income::factory()->create([
                'project_id' => $project->id,
                'created_by_user_id' => null,
                'costs' => null,
                'distribution' => null
            ]);
        }

        \App\Models\Income::factory(10)->create();
    }
}
