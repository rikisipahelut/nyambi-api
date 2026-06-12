<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_categories', function (Blueprint $table) {
            $table->foreignUuid('worker_id')->constrained('worker_profiles')->cascadeOnDelete();
            $table->string('category_id', 50);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->primary(['worker_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_categories');
    }
};
