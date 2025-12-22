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
        Schema::create('work_areas', function (Blueprint $table) {
            $table->id();
            $table->string('work_area_name')->nullable()->comment('Work area name');
            $table->string('description')->nullable()->comment('Work area description');
            $table->string('status')->nullable()->default('active')->comment('Work area status');
            $table->string('addedby')->nullable()->comment('User ID who added this work area');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_areas');
    }
};

