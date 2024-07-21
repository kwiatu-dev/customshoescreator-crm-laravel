<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('remarks', 150)->default('');
            $table->decimal('price', 8, 2, true);
            $table->date('start');
            $table->date('deadline');
            $table->unsignedTinyInteger('commission');
            $table->unsignedTinyInteger('costs');
            $table->json('distribution');
            $table->decimal('visualization', 8, 2, true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreignIdFor(
                \App\Models\User::class,
                'created_by_user_id'
            )->constrained('users');

            $table->foreignIdFor(
                \App\Models\Client::class,
                'client_id'
            )->constrained('clients');

            $table->foreignIdFor(
                \App\Models\ProjectStatus::class,
                'status_id'
            )->constrained('project_statuses');

            $table->foreignIdFor(
                \App\Models\ProjectStatus::class,
                'type_id'
            )->constrained('project_types');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
