<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->enum('item_type', ['service','addon']);
            $table->uuid('item_id');
            $table->string('name_snapshot');
            $table->unsignedInteger('price_snapshot');
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
        Schema::dropIfExists('booking_items');
    }
};
