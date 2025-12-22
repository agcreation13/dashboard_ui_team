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
        Schema::create('daily_material_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable()->comment('Related site ID');
            $table->date('material_add_date')->nullable()->comment('Date of material addition');
            $table->longText('material_data')->nullable()->comment('Stores array of material ID, shift, and remark');
            $table->unsignedBigInteger('added_by')->nullable()->comment('User who added the record');
            $table->string('daily_material_status')->default('active');
            $table->softDeletes();      // adds deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_material_entries');
    }
};
