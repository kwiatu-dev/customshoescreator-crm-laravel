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
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->date('start');
            $table->date('end');
            $table->string('remarks', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();

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
                'type_id'
            )->constrained('user_event_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
};
