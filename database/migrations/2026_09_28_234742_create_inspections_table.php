<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->index();
            $table->dateTime('inspected_at')->index();
            $table->foreignId('inspected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('odometer')->nullable();
            $table->string('fuel_level', 20)->nullable();
            $table->string('exterior_condition', 20)->nullable();
            $table->string('interior_condition', 20)->nullable();
            $table->string('tire_condition', 20)->nullable();
            $table->string('completeness', 20)->nullable();
            $table->text('missing_items')->nullable();
            $table->text('existing_damage')->nullable();
            $table->text('new_damage')->nullable();
            $table->text('notes')->nullable();
            $table->string('vehicle_status_after', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
