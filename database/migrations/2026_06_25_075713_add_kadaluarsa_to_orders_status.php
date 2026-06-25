<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('menunggu','dikonfirmasi','selesai','dibatalkan','kadaluarsa') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('menunggu','dikonfirmasi','selesai','dibatalkan') DEFAULT 'menunggu'");
    }
};
