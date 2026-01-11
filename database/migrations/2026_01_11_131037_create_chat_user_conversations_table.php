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
        Schema::create('chat_user_conversations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->text('session_id');

            $table->foreignIdFor(
                \App\Models\User::class,
                'user_id'
            )
            ->constrained('users');

            $table->timestamps();
            $table->unique(['user_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_user_conversations');
    }
};
