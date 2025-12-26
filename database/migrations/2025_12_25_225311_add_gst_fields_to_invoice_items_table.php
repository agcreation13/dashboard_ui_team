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
            $table->string('hsn')->nullable()->after('product_name');
            $table->string('pack')->nullable()->after('hsn');
            $table->integer('free_quantity')->default(0)->after('quantity');
            $table->decimal('mrp', 10, 2)->nullable()->after('pack');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount');
            $table->decimal('gst_percentage', 5, 2)->default(0)->after('tax');
            $table->decimal('gst_amount', 10, 2)->default(0)->after('gst_percentage');
            $table->decimal('net_amount', 10, 2)->default(0)->after('gst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn([
                'hsn', 'pack', 'free_quantity', 'mrp', 
                'discount_percentage', 'gst_percentage', 'gst_amount', 'net_amount'
            ]);
        });
    }
};
