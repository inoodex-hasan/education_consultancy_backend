<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix journal_entries.created_by: RESTRICT → SET NULL
        // Accounting records should persist even if user is deleted
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        // 2. Remove remaining_balance column from office_accounts
        // Balance will be computed from journal entries dynamically
        if (Schema::hasColumn('office_accounts', 'remaining_balance')) {
            Schema::table('office_accounts', function (Blueprint $table) {
                $table->dropColumn('remaining_balance');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert journal_entries.created_by back to RESTRICT
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });

        // Re-add remaining_balance column
        if (!Schema::hasColumn('office_accounts', 'remaining_balance')) {
            Schema::table('office_accounts', function (Blueprint $table) {
                $table->decimal('remaining_balance', 15, 2)->default(0)->after('opening_balance');
            });
        }
    }
};
