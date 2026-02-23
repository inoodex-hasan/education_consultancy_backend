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
        Schema::create('office_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->enum('account_type', ['bank', 'mfs', 'cash']);
            $table->string('provider_name')->nullable(); // e.g. DBBL, bKash
            $table->string('account_number');
            $table->string('branch_name')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_accounts');
    }
};
