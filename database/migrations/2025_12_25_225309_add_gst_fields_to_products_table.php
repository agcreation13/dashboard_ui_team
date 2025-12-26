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
        Schema::table('products', function (Blueprint $table) {
            $table->string('hsn')->nullable()->after('sku');
            $table->string('pack')->nullable()->after('hsn');
            $table->decimal('mrp', 10, 2)->nullable()->after('selling_price');
            $table->decimal('gst_percentage', 5, 2)->default(0)->after('mrp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hsn', 'pack', 'mrp', 'gst_percentage']);
        });
    }
};
