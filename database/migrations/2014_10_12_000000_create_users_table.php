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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 320)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->string('phone', 20)->unique();
            $table->string('street', 50)->nullable();
            $table->string('street_nr', 10)->nullable();
            $table->string('apartment_nr', 10)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('country', 25)->nullable();
            $table->smallInteger('commission');
            $table->smallInteger('costs');
            $table->json('distribution');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
