<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('customer_vehicle_id');
            $table->uuid('coverage_area_id');
            $table->string('code')->unique();
            $table->text('address');
            $table->date('booking_date');
            $table->string('booking_time_slot');
            $table->unsignedInteger('total_service_price');
            $table->unsignedInteger('total_addon_price')->default(0);
            $table->unsignedInteger('area_fee')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('required_dp_amount');
            $table->unsignedInteger('amount_paid')->default(0);
            $table->enum('payment_status', ['unpaid','partial_dp','paid','refunded'])->default('unpaid');
            $table->enum('status', ['pending','confirmed','assigned','in_progress','completed','cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_vehicle_id')
                ->references('id')
                ->on('customer_vehicles')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // vehicle_type is resolved via customer_vehicle -> vehicle_types

            $table->foreign('coverage_area_id')
                ->references('id')
                ->on('coverage_areas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
