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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Name of the material');
            $table->string('unit_type')->nullable()->comment('Unit Type of the material');
            $table->decimal('price_byunit', 15, 2)->nullable()->comment('Price per unit of the material (₹)');
            $table->string('status')->nullable()->default('active')->comment('Status of the material');
            $table->string('addedBy')->default('1')->comment('User who added the material');
            $table->longText('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
