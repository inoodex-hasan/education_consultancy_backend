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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['advance', 'partial', 'final']);
            $table->dateTime('payment_date');
            $table->foreignId('collected_by')->constrained('users')->cascadeOnDelete();
            $table->string('receipt_number', 50)->nullable();
            $table->enum('payment_status', ['pending', 'completed'])->default('pending');
            $table->timestamps();

            $table->index(['student_id', 'payment_type', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
