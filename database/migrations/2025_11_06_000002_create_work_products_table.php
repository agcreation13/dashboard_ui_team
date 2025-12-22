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
        Schema::create('work_products', function (Blueprint $table) {
            $table->id();
            $table->string('work_product_name')->nullable()->comment('Work product name');
            $table->decimal('rate', 15, 2)->nullable()->comment('Rate per unit');
            $table->string('description')->nullable()->comment('Work product description');
            $table->string('status')->nullable()->default('active')->comment('Work product status');
            $table->string('addedby')->nullable()->comment('User ID who added this work product');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_products');
    }
};

