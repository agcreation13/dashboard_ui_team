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
        Schema::create('site_billings', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->nullable()->comment('Reference to the site associated with the bill');
            $table->string('site_bill_no')->nullable()->comment('Unique bill number issued for the site');
            $table->string('site_bill_date')->nullable()->comment('Date when the bill was issued');
            $table->decimal('site_bill_value', 15, 2)->nullable()->comment('Total value or amount of the site bill (₹)');
            $table->string('site_bill_file')->nullable()->comment('Uploaded file or document related to the site bill');
            $table->string('status')->default('open')->comment('Current status of the site bill (e.g., open, closed)');
            $table->string('addedBy')->default('1')->comment('User ID who added the site bill entry');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_billings');
    }
};
