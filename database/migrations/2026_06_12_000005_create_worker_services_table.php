<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_services', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('worker_id')->constrained('worker_profiles')->cascadeOnDelete();
            $table->string('name', 100);
            $table->integer('price')->nullable();
            $table->string('unit', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_services');
    }
};
