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
            $table->boolean('security_deposit_status')->default(false)->after('final_status');
            $table->boolean('cvu_fee_status')->default(false)->after('security_deposit_status');
            $table->boolean('admission_fee_status')->default(false)->after('cvu_fee_status');
            $table->boolean('final_payment_status')->default(false)->after('admission_fee_status');
            $table->boolean('emgs_payment_status')->default(false)->after('final_payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'security_deposit_status',
                'cvu_fee_status',
                'admission_fee_status',
                'final_payment_status',
                'emgs_payment_status',
            ]);
        });
    }
};
