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
        if (Schema::hasTable('chat_messages')) {
            Schema::drop('chat_messages');
        }

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('session_id');
            $table->jsonb('message');

            $table->timestampTz('created_at')->default(DB::raw('now()'));

            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
