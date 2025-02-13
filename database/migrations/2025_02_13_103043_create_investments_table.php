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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->decimal('amount', 8, 2, true);
            $table->date('date');
            $table->unsignedTinyInteger('interest_rate');
            $table->decimal('total_repayment', 8, 2, true)->default(0.0);
            $table->string('remarks', 150)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreignIdFor(
                \App\Models\User::class,
                'created_by_user_id'
            )->constrained('users');

            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )->constrained('users');

            $table->foreignIdFor(
                \App\Models\ProjectStatus::class,
                'status_id'
            )->constrained('investment_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
