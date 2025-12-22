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
        Schema::create('check_update_sites', function (Blueprint $table) {
            $table->id();
            $table->string('check_date')->nullable();
            $table->longText('check_site')->nullable();
            $table->string('check_by')->nullable();
            $table->string('check_type')->nullable();
            $table->string('check_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_update_sites');
    }
};
