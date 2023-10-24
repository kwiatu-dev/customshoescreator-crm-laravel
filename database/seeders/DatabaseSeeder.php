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
        \App\Models\User::factory()->create([
            'first_name' => 'Filip',
            'last_name' => 'Kwiatkowski',
            'email' => 'test@example.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->create([
            'first_name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        \App\Models\Listing::factory(10)->create([
            'by_user_id' => 1,
        ]);

        
        \App\Models\Listing::factory(10)->create([
            'by_user_id' => 2,
        ]);

        \App\Models\Client::factory(20)->create([
            'created_by_user_id' => 1,
        ]);

        \App\Models\User::factory(10)->create();

        \App\Models\Expenses::factory(10)->create([
            'created_by_user_id' => 1,
        ]);

        \App\Models\Project::factory(20)->create();
    }
}
