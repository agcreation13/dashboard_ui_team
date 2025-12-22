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
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->nullable()->comment('Reference to the site associated with the payment receipt');
            $table->string('site_bill_no')->nullable()->comment('Bill number issued for the site');
            $table->string('receipts_date')->nullable()->comment('Date of the payment receipt');
            $table->string('receipts_no')->nullable()->comment('Unique number of the payment receipt');
            $table->decimal('receipts_value', 15, 2)->nullable()->comment('Value or amount of the payment receipt (₹)');
            $table->string('receipts_file')->nullable()->comment('Uploaded file or document related to the receipt');
            $table->string('receipts_pay_type')->nullable()->comment('Add Payment type');
            $table->string('status')->default('open')->comment('Current status of the payment receipt (e.g., open, closed)');
            $table->string('addedBy')->default('1')->comment('User ID who added the payment receipt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
