<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('office_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_account_id')->nullable()->constrained('office_accounts')->onDelete('set null');
            $table->foreignId('to_account_id')->nullable()->constrained('office_accounts')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->date('transaction_date');
            $table->enum('transaction_type', ['transfer', 'deposit', 'withdrawal']);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_transactions');
    }
};
