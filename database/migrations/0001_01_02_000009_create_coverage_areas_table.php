<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coverage_areas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->unsignedInteger('fee')->default(0);
            $table->string('note')->nullable();
            $table->boolean('requires_admin_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverage_areas');
    }
};
