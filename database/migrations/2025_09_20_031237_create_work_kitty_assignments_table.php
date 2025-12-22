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
        Schema::create('work_kitty_assignments', function (Blueprint $table) {
            $table->id();
        
            $table->string('site_id')->nullable()->comment('Reference to site ID');
            $table->string('labour_id')->nullable()->comment('Reference to labour ID');
            $table->string('work_kitty_id')->nullable()->comment('Reference to workkitty_lists.id');
        
            $table->date('start_date')->nullable()->comment('Start date of the assignment');
            $table->date('total_work_day')->nullable()->comment('Total Working day assignment');
            $table->date('end_date')->nullable()->comment('End date of the assignment');
        
            $table->string('incentive_plan')->nullable()->comment('Incentive plan details');
            $table->date('actual_date')->nullable()->comment('Actual date of work done');
            $table->string('incentive_value')->nullable()->comment('Incentive value or score');
        
            $table->string('verified')->default(false)->comment('Whether the assignment is verified');
            $table->string('verified_by')->nullable()->comment('User ID who verified the assignment');
            $table->date('verified_date')->nullable()->comment('Date when verification occurred');
        
            $table->longText('remark')->nullable()->comment('Additional remarks or comments');
            $table->string('other')->nullable()->comment('Other relevant information');
        
            $table->string('added_by')->nullable()->comment('User ID of who added this record');
            $table->string('status')->nullable()->comment('Status of the assignment (e.g., process, pending, completed)');
        
            $table->timestamps();
        
            // Optional: foreign key constraints if applicable
            // $table->foreign('work_kitty_id')->references('id')->on('workkitty_lists')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_kitty_assignments');
    }
};
