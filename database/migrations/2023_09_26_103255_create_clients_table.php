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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 320)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->unique();
            $table->string('street', 50)->nullable();
            $table->string('street_nr', 10)->nullable();
            $table->string('apartment_nr', 10)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('country', 25)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('social_link', 255)->nullable();
            $table->softDeletes();

            $table->foreignIdFor(
                \App\Models\User::class,
                'created_by_user_id'
            )->constrained('users');


            $table->foreignIdFor(
                \App\Models\User::class,
                'conversion_source_id'
            )->constrained('conversion_sources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
