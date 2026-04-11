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
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('offer_letter_received')->default(false)->after('status');
            $table->date('offer_letter_received_date')->nullable()->after('offer_letter_received');
            $table->boolean('vfs_appointment')->default(false)->after('offer_letter_received_date');
            $table->date('vfs_appointment_date')->nullable()->after('vfs_appointment');
            $table->boolean('file_submission')->default(false)->after('vfs_appointment_date');
            $table->date('file_submission_date')->nullable()->after('file_submission');
            $table->enum('visa_status', ['not_applied', 'pending', 'approved', 'rejected'])->default('not_applied')->after('file_submission_date');
            $table->date('visa_decision_date')->nullable()->after('visa_status');
            $table->date('visa_approval_date')->nullable()->after('visa_decision_date');
            $table->enum('tuition_fee_status', ['pending', 'paid', 'partial'])->default('pending')->after('tuition_fee');
            $table->enum('service_charge_status', ['pending', 'paid', 'partial'])->default('pending')->after('tuition_fee_status');
            $table->enum('application_priority', ['normal', 'priority', 'vip'])->default('normal')->after('service_charge_status');
            $table->text('internal_notes')->nullable()->after('application_priority');
            $table->json('documents_checklist')->nullable()->after('internal_notes');
            $table->enum('final_status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('documents_checklist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'offer_letter_received',
                'offer_letter_received_date',
                'vfs_appointment',
                'vfs_appointment_date',
                'file_submission',
                'file_submission_date',
                'visa_status',
                'visa_decision_date',
                'visa_approval_date',
                'tuition_fee_status',
                'service_charge_status',
                'application_priority',
                'internal_notes',
                'documents_checklist',
                'final_status',
            ]);
        });
    }
};
