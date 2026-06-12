<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('worker_id')->constrained('worker_profiles')->cascadeOnDelete();
            $table->string('tag', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_tags');
    }
};
