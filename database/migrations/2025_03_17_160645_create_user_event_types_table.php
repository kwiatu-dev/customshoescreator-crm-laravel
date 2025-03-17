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
        Schema::create('user_event_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
        });

        DB::table('user_event_types')->insert([
            ['name' => 'Dni wolne'],
            ['name' => 'Inne']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_event_types');
    }
};
