<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_price_tiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_id');
            $table->uuid('vehicle_type_id');
            $table->unsignedInteger('price');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('vehicle_type_id')
                ->references('id')
                ->on('vehicle_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_price_tiers');
    }
};
