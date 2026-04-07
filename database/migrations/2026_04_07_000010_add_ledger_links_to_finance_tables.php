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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'invoice_id')) {
                $table->foreignId('invoice_id')->nullable()->after('application_id')->constrained('invoices');
            }
            if (!Schema::hasColumn('payments', 'journal_entry_id')) {
                $table->foreignId('journal_entry_id')->nullable()->after('office_account_id')->constrained('journal_entries');
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'chart_of_account_id')) {
                $table->foreignId('chart_of_account_id')->nullable()->after('id')->constrained('chart_of_accounts');
            }
            if (!Schema::hasColumn('expenses', 'journal_entry_id')) {
                $table->foreignId('journal_entry_id')->nullable()->after('salary_id')->constrained('journal_entries');
            }
        });

        Schema::table('office_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('office_accounts', 'chart_of_account_id')) {
                $table->foreignId('chart_of_account_id')->nullable()->after('account_number')->constrained('chart_of_accounts');
            }
        });

        Schema::table('salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('salaries', 'journal_entry_id')) {
                $table->foreignId('journal_entry_id')->nullable()->after('transaction_id')->constrained('journal_entries');
            }
        });

        Schema::table('commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('commissions', 'journal_entry_id')) {
                $table->foreignId('journal_entry_id')->nullable()->after('status')->constrained('journal_entries');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('office_accounts', function (Blueprint $table) {
            $table->dropForeign(['chart_of_account_id']);
            $table->dropColumn('chart_of_account_id');
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });
    }
};
