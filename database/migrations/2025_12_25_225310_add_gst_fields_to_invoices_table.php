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
        Schema::table('invoices', function (Blueprint $table) {
            // Seller/Company details
            $table->string('seller_name')->nullable()->after('invoice_number');
            $table->text('seller_address')->nullable()->after('seller_name');
            $table->string('seller_email')->nullable()->after('seller_address');
            $table->string('seller_phone')->nullable()->after('seller_email');
            $table->string('seller_gstin')->nullable()->after('seller_phone');
            
            // Additional invoice fields
            $table->string('eway_bill')->nullable()->after('invoice_date');
            $table->string('mr_no')->nullable()->after('eway_bill');
            $table->string('s_man')->nullable()->after('mr_no');
            
            // Buyer GSTIN
            $table->string('customer_gstin')->nullable()->after('customer_address');
            
            // Tax breakdown
            $table->decimal('cgst_percentage', 5, 2)->default(0)->after('tax');
            $table->decimal('cgst_amount', 10, 2)->default(0)->after('cgst_percentage');
            $table->decimal('sgst_percentage', 5, 2)->default(0)->after('cgst_amount');
            $table->decimal('sgst_amount', 10, 2)->default(0)->after('sgst_percentage');
            
            // Additional amounts
            $table->decimal('additional_amount', 10, 2)->default(0)->after('sgst_amount');
            $table->decimal('round_off', 10, 2)->default(0)->after('additional_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'seller_name', 'seller_address', 'seller_email', 'seller_phone', 'seller_gstin',
                'eway_bill', 'mr_no', 's_man', 'customer_gstin',
                'cgst_percentage', 'cgst_amount', 'sgst_percentage', 'sgst_amount',
                'additional_amount', 'round_off'
            ]);
        });
    }
};
