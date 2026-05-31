<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY type VARCHAR(255) NULL");
        DB::statement("ALTER TABLE transactions MODIFY amount DECIMAL(10,2) NULL");
        DB::statement("ALTER TABLE transactions MODIFY status VARCHAR(255) NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE transactions MODIFY details TEXT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY type VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE transactions MODIFY amount DECIMAL(10,2) NOT NULL");
        DB::statement("ALTER TABLE transactions MODIFY status VARCHAR(255) NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE transactions MODIFY details TEXT NOT NULL");
    }
};
