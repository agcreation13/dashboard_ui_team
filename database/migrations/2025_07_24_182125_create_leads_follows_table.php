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
        Schema::create('leads_follows', function (Blueprint $table) {
            $table->id();
            $table->string('leads_id')->nullable()->comment('Reference to the leads associated with the follow-up');
            $table->longText('leads_follow_remark')->nullable()->comment('Unique follow-up number issued for the leads');
            $table->string('leads_follow_date')->nullable()->comment('Date when the follow-up was made');
            $table->string('leads_follow_action')->nullable()->comment('Action taken during the follow-up');
            $table->string('leads_follow_next_date')->nullable()->comment('Next follow-up date');
            $table->string('leads_stage')->default('other')->comment('Current stage of the site bill (e.g., open, closed, deleted)');
            $table->string('leads_follow_status')->default('open')->comment('Current status of the site bill (e.g., open, closed, deleted)');
            $table->string('leads_handled_by')->default('1')->comment('User ID who added the site bill entry');
            $table->string('leads_follow_By')->default('1')->comment('User ID who added the site bill entry');
            // soft delete option 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_follows');
    }
};
