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
        Schema::create('complaints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('filed_by')->constrained('users')->cascadeOnDelete();
            $table->string('filed_as', 10);  // customer | worker
            $table->string('tipe', 50);
            // pekerjaan_tidak_sesuai|pekerja_tidak_hadir|pekerjaan_tidak_selesai|customer_tidak_ada|lainnya
            $table->text('deskripsi');
            $table->string('status', 30)->default('terbuka');
            // terbuka | diselesaikan | ditutup | diselesaikan_admin
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
