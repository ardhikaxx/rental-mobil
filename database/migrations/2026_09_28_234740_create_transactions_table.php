<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 40)->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('booking_source', 30)->default('walk_in');
            $table->dateTime('start_at')->index();
            $table->dateTime('end_at')->index();
            $table->dateTime('handover_at')->nullable();
            $table->foreignId('handed_over_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('actual_return_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('daily_rate');
            $table->unsignedInteger('rental_days');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('total');
            $table->unsignedInteger('late_minutes')->default(0);
            $table->unsignedBigInteger('late_fee')->default(0);
            $table->string('status', 30)->default('awaiting_payment')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'start_at', 'end_at']);
            $table->index(['status', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
