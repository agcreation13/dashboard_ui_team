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
        Schema::create('remarks', function (Blueprint $table) {
            $table->id();
            $table->longText('remark_text')->nullable()->comment('The text of the remark');
            $table->string('remarkable_type')->nullable()->default(NULL)->comment('Type of the model that the remark is associated with, e.g., SiteDetail, SiteBilling, PaymentReceipt');
            $table->string('remarkable_id')->nullable()->comment('ID of the model that the remark is associated with');
            $table->string('remark_type')->nullable()->comment('Type of remark, e.g., site, billing, payment, etc.');
            $table->string('remark_status')->default('active'); // active, inactive, delete
            $table->string('addedBy')->default('1'); // active, inactive, delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remarks');
    }
};
