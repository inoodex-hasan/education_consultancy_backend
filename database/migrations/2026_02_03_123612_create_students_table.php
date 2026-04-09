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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->date('dob')->nullable();

            // Academic Background
            $table->string('ssc_result')->nullable();
            $table->string('hsc_result')->nullable();
            $table->string('ielts_score')->nullable();
            $table->string('subject')->nullable();

            // Preferred Academic Destination
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('course_intake_id')->nullable()->constrained('course_intakes')->nullOnDelete();

            // $table->enum('current_stage', [
            //     'lead',
            //     'counseling',
            //     'payment',
            //     'application',
            //     'offer',
            //     'visa',
            //     'enrolled',
            // ])->nullable();
            // $table->enum('current_status', [
            //     'pending',
            //     'applied',
            //     'rejected',
            //     'withdrawn',
            //     'visa_processing',
            //     'enrolled',
            // ])->nullable();

            $table->foreignId('assigned_marketing_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_application_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->json('documents')->nullable();

            $table->timestamps();

            $table->index(['current_stage', 'current_status']);
            $table->index(['assigned_marketing_id', 'assigned_consultant_id', 'assigned_application_id', 'created_by'], 'students_assignment_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
