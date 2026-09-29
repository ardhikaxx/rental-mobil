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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('sim_number', 50)->nullable()->after('id_number');
            $table->string('ktp_photo')->nullable()->after('notes');
            $table->string('sim_photo')->nullable()->after('ktp_photo');
            $table->string('verification_status', 20)->default('verified')->after('sim_photo');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->string('rejection_reason')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'sim_number',
                'ktp_photo',
                'sim_photo',
                'verification_status',
                'verified_at',
                'verified_by',
                'rejection_reason',
            ]);
        });
    }
};
