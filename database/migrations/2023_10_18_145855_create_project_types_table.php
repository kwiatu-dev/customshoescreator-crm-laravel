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
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
        });

        DB::table('project_types')->insert([
            ['name' => 'Renowacja butów'],
            ['name' => 'Personalizacja butów'],
            ['name' => 'Personalizacja ubrań'],
            ['name' => 'Haft ręczny'],
            ['name' => 'Haft komputerowy'],
            ['name' => 'Inne']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_types');
    }
};
