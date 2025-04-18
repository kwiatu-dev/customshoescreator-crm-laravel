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
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedTinyInteger('costs')->nullable();
            $table->json('distribution')->nullable();
            $table->unsignedTinyInteger('commission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('costs');
            $table->dropColumn('distribution');
            $table->dropColumn('commission');
        });
    }
};
