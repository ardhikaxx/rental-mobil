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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('with_driver')->default(false)->after('vehicle_id');
            $table->foreignId('driver_id')->nullable()->after('with_driver')->constrained('drivers')->nullOnDelete();
            $table->unsignedBigInteger('driver_rate')->default(0)->after('daily_rate');
            $table->unsignedBigInteger('driver_fee')->default(0)->after('driver_rate');

            $table->string('deposit_type', 50)->nullable()->after('late_fee');
            $table->unsignedBigInteger('deposit_amount')->default(0)->after('deposit_type');
            $table->string('deposit_status', 20)->default('none')->after('deposit_amount');
            $table->text('deposit_notes')->nullable()->after('deposit_status');
            $table->dateTime('deposit_refunded_at')->nullable()->after('deposit_notes');
            $table->foreignId('deposit_refunded_by')->nullable()->after('deposit_refunded_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['deposit_refunded_by']);
            $table->dropColumn([
                'with_driver',
                'driver_id',
                'driver_rate',
                'driver_fee',
                'deposit_type',
                'deposit_amount',
                'deposit_status',
                'deposit_notes',
                'deposit_refunded_at',
                'deposit_refunded_by',
            ]);
        });
    }
};
