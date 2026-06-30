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
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('vehicle_type_id');
            $table->string('brand');
            $table->string('model');
            $table->string('plate_number')->unique();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('color')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('vehicle_type_id');

            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('vehicle_type_id')
                ->references('id')
                ->on('vehicle_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_vehicles');
    }
};
