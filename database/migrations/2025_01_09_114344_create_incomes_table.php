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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 50);
            $table->unsignedDecimal('price', 8, 2);
            $table->date('date')->nullable();
            $table->string('remarks', 150)->nullable();
            $table->softDeletes();

            $table->foreignIdFor(
                \App\Models\Project::class,
                'project_id'
            )
            ->nullable()
            ->unique()
            ->constrained('projects');

            $table->foreignIdFor(
                \App\Models\IncomeStatus::class,
                'status_id'
            )->constrained('income_statuses');

            $table->foreignIdFor(
                \App\Models\User::class,
                'created_by_user_id'
            )->nullable()
            ->constrained('users');
        });

        DB::statement('ALTER TABLE incomes ADD CONSTRAINT check_project_or_user CHECK (project_id IS NOT NULL OR created_by_user_id IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
