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
        Schema::create('daily_labour_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable()->comment('Related Site ID');
            $table->date('plan_of_date')->nullable()->comment('Date of daily work entry');
            $table->longText('plan_message')->nullable()->comment('Daily work plan or message');
            $table->longText('labour_data')->nullable()->comment('Stores array of labour ID, shift, and remark');
            $table->unsignedBigInteger('added_by')->nullable()->comment('User who added this entry');
            $table->string('daily_labour_status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_labour_entries');
    }
};
