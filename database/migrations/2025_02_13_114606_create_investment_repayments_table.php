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
        Schema::create('investment_repayments', function (Blueprint $table) {
            $table->id();
            $table->decimal('repayment', 8, 2, true);
            $table->date('date');
            $table->string('remarks', 150)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreignIdFor(
                \App\Models\Investment::class,
                'investment_id'
            )->constrained('investments');

            $table->foreignIdFor(
                \App\Models\User::class,
                'created_by_user_id'
            )->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_repayments');
    }
};
