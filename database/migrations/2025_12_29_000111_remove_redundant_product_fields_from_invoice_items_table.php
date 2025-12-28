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
        Schema::table('invoice_items', function (Blueprint $table) {
            // Remove redundant product fields - data will be retrieved via product relationship
            // Keep: product_id, quantity, free_quantity, rate, discount_percentage, net_amount
            $table->dropColumn([
                'product_name',
                'hsn',
                'pack',
                'mrp',
                'discount', // redundant, using discount_percentage
                'tax', // redundant, can be calculated
                'gst_percentage', // get from product
                'gst_amount', // can be calculated
                'line_total', // same as net_amount
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            // Restore product fields for rollback
            $table->string('product_name')->after('product_id');
            $table->string('hsn')->nullable()->after('product_name');
            $table->string('pack')->nullable()->after('hsn');
            $table->decimal('mrp', 10, 2)->nullable()->after('pack');
            $table->decimal('discount', 10, 2)->default(0)->after('discount_percentage');
            $table->decimal('tax', 10, 2)->default(0)->after('gst_amount');
            $table->decimal('gst_percentage', 5, 2)->default(0)->after('tax');
            $table->decimal('gst_amount', 10, 2)->default(0)->after('gst_percentage');
            $table->decimal('line_total', 10, 2)->default(0)->after('net_amount');
        });
    }
};
