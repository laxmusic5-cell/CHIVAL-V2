<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->string('provider')->nullable();
            $table->string('provider_payment_id')->nullable();
            $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->unsignedInteger('amount')->default(0);
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
