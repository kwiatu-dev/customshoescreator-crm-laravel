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
        Schema::create('income_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
        });

        DB::table('income_statuses')->insert([
            ['name' => 'Oczekujący'],
            ['name' => 'Rozliczony'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_statuses');
    }
};
