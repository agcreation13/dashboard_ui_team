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
            // Remove seller fields - data will be retrieved from config file
            $table->dropColumn([
                'seller_name',
                'seller_address',
                'seller_email',
                'seller_phone',
                'seller_gstin',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Restore seller fields for rollback
            $table->string('seller_name')->nullable()->after('invoice_number');
            $table->text('seller_address')->nullable()->after('seller_name');
            $table->string('seller_email')->nullable()->after('seller_address');
            $table->string('seller_phone')->nullable()->after('seller_email');
            $table->string('seller_gstin')->nullable()->after('seller_phone');
        });
    }
};
