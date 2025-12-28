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
            // Remove redundant customer fields - data will be retrieved via customer relationship
            $table->dropColumn([
                'customer_name',
                'customer_mobile',
                'customer_email',
                'customer_address',
                'customer_gstin',
                'customer_state',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Restore customer fields for rollback
            $table->string('customer_name')->after('customer_id');
            $table->string('customer_mobile')->nullable()->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_mobile');
            $table->text('customer_address')->nullable()->after('customer_email');
            $table->string('customer_gstin')->nullable()->after('customer_address');
            $table->string('customer_state')->nullable()->after('customer_gstin');
        });
    }
};
