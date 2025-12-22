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
        Schema::create('leadsheets', function (Blueprint $table) {
            $table->id();
            $table->date('leads_entery_date')->nullable()->comment('Date when the lead was entered');
            $table->string('leads_client_id')->nullable()->comment('Unique identifier for the client or site');
            $table->string('leads_client_type')->nullable()->comment('Client type: Owner, Architect, Turnkey, or Other');
            $table->string('leads_source_name')->nullable()->comment('Lead source: Instagram, Online Message, Call, Reference, etc.');
            $table->string('leads_address')->nullable()->comment('Short address of the client');
            $table->string('leads_email')->nullable()->comment('Email of the client');
            $table->string('leads_phoneno')->nullable()->comment('Primary phone number of the client');
            $table->string('leads_phoneno2')->nullable()->comment('Primary phone number 2 of the client');
            $table->string('leads_name')->comment('Name of the client or site');
            $table->string('leads_status')->default('open')->comment('Current status:  Open, Good, Superb, or Closed');
            $table->string('leads_stage')->nullable()->comment('Current stage: Initial, Rates Shared, Visit, Sampling, etc.');
            $table->string('leads_handled_by')->nullable()->comment('How the lead is being handled: user id or name');
            $table->longText('leads_remark')->nullable()->comment('Remarks or notes about the lead');
            $table->longText('leads_story')->nullable()->comment('Detailed story or history of the lead');
            $table->string('addedBy')->default(0)->comment('User ID or name who added this lead');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leadsheets');
    }
};
