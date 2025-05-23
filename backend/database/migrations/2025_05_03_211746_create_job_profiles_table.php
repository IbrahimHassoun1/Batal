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
        Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['trainer', 'dietitian']);
            $table->string('job_description')->nullable();
            $table->decimal('hourly_rate', 5, 2)->unsigned();
            $table->boolean('negotiable')->default(false);
            $table->decimal('years_of_experience', 3, 1);
            $table->decimal('rating', 3, 2)->default(0.0);
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profiles');
    }
};
