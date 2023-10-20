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
            ['name' => 'renowacja butów'],
            ['name' => 'personalizacja butów'],
            ['name' => 'personalizacja ubrań'],
            ['name' => 'haft ręczny'],
            ['name' => 'haft komputerowy'],
            ['name' => 'inne']
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
