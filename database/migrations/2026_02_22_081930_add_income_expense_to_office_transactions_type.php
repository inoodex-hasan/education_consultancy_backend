<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE office_transactions MODIFY COLUMN transaction_type ENUM('transfer', 'deposit', 'withdrawal', 'income', 'expense') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE office_transactions MODIFY COLUMN transaction_type ENUM('transfer', 'deposit', 'withdrawal') NOT NULL");
    }
};
