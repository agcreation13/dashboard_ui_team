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
        Schema::create('site_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the site');
            $table->string('email')->default(NULL)->comment('Email of the site owner');
            $table->string('phoneno')->default(NULL)->comment('Phone number of the site owner');
            $table->string('site_type')->nullable()->comment('Type of the site');
            $table->string('owner_name')->nullable()->comment('Owner Name');
            $table->string('designer_name')->nullable()->comment('Designer Name');
            $table->string('designer_phone_no')->nullable()->comment('Designer Phone No');
            $table->longText('address')->nullable()->comment('Address');
            $table->date('start_date')->nullable()->comment('Start Date');
            $table->string('project_id')->nullable()->comment('Project ID');
            $table->string('representative')->nullable()->comment('Representative');
            $table->string('supervisor')->nullable()->comment('Supervisor');
            $table->string('mukadam')->nullable()->comment('Mukadam');
            $table->string('status')->default('active')->comment('Status');
            $table->decimal('bill_value', 15, 2)->nullable()->comment('Bill Value (₹)');
            $table->decimal('bill_value_old', 15, 2)->nullable()->comment('Bill Value Old save for history (₹)');
            $table->decimal('payment_received', 15, 2)->nullable()->comment('Payment Received (₹)');
            $table->date('next_payment_date')->nullable()->comment('Next Payment Date');
            $table->boolean('standby')->default(false)->comment('Is site on standby');
            $table->boolean('closeby')->default(false)->comment('Is site on closeby');
            $table->longText('standby_reason')->nullable()->comment('Reason for standby');
            $table->longText('close_reason')->nullable()->comment('Reason for Close');
            $table->string('addedBy')->default(0)->comment('Added by (user name)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_details');
    }
};
