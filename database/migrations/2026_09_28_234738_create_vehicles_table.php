<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('brand', 60);
            $table->string('model', 60);
            $table->string('type', 40)->index();
            $table->unsignedSmallInteger('year');
            $table->string('color', 40);
            $table->string('license_plate', 20)->unique();
            $table->string('chassis_number', 60)->nullable();
            $table->string('engine_number', 60)->nullable();
            $table->unsignedBigInteger('daily_rate');
            $table->string('photo')->nullable();
            $table->string('status', 30)->default('tersedia')->index();
            $table->unsignedBigInteger('odometer')->default(0);
            $table->string('fuel_level', 20)->default('full');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
