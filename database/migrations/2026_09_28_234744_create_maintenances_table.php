<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->string('type', 30)->default('routine');
            $table->string('status', 30)->default('scheduled')->index();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('odometer')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('cost')->default(0);
            $table->string('workshop')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
