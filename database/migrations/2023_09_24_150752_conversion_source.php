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
        Schema::create('conversion_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
        });

        DB::table('conversion_sources')->insert([
            ['name' => 'Facebook'],
            ['name' => 'Instagram'],
            ['name' => 'TikTok'],
            ['name' => 'Google'],
            ['name' => 'Olx'],
            ['name' => 'Allegro'],
            ['name' => 'Znajomi'],
            ['name' => 'Inne']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_sources');
    }
};
