<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('specialty', 100);
            $table->text('bio')->nullable();
            $table->string('location', 100)->nullable();
            $table->enum('status', ['available', 'busy'])->default('available');
            $table->smallInteger('experience_years')->default(0);
            $table->string('response_time', 50)->nullable();
            $table->integer('completed_jobs')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->text('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_profiles');
    }
};
