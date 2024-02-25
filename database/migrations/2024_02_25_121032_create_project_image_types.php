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
        Schema::create('project_image_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
        });

        DB::table('project_image_types')->insert([
            ['name' => 'Inspiracje'],
            ['name' => 'Wizualizacje komputerowe'],
            ['name' => 'Zdjęcia końcowe'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_image_types');
    }
};
