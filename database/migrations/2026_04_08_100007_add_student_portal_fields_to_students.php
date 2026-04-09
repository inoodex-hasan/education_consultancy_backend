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
        Schema::table('students', function (Blueprint $table) {
            // Student portal password
            $table->string('password')->nullable()->after('email');

            // Sponsor phone
            $table->string('sponsor_phone')->nullable()->after('phone');

            // Passport validity
            $table->date('passport_validity')->nullable()->after('passport_number');

            // Translation documents (store file paths as JSON)
            $table->json('translation_documents')->nullable()->after('passport_validity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['password', 'sponsor_phone', 'passport_validity', 'translation_documents']);
        });
    }
};
