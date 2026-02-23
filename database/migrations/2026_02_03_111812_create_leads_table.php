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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('current_education')->nullable();
            $table->string('preferred_country')->nullable();
            $table->string('preferred_course')->nullable();
            $table->string('source')->nullable(); 
            $table->string('status')->default('pending'); 
            $table->text('notes')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('consultant_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
