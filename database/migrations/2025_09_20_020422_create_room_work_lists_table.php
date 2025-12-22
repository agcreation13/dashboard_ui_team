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
         Schema::create('room_work_lists', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->nullable()->comment('Reference to site/project');
            $table->string('room_id')->nullable()->comment('Reference to the room room_lists id');
            $table->string('room_sr_no')->nullable()->comment('Room serial number form room_details under sr/id');
            $table->string('work_area_name')->nullable()->comment('Work area name');
            $table->string('work_product_name')->nullable()->comment('Work product name');
            $table->string('unit')->nullable()->comment('add SFT/RFT');
            $table->string('work_area_quantity')->nullable()->comment('Quantity for the work area SFT/RFT');
            $table->string('rate')->nullable()->comment('Rate per unit SFT/RFT');  
            $table->string('total_value')->nullable()->comment('add total value of SFT + RFT'); // add to other tbale work process lists
            $table->string('day_count')->nullable()->comment('working day count of workdays'); // add to other tbale work process lists
            $table->string('workplan_percentage')->nullable()->comment('P1 percentage/ P2 percentage');        
            $table->string('workplan_percentage_value')->nullable()->comment('P1 / P2 calculated value');
            $table->text('workplan_description')->nullable()->comment('P1 / P2 work description');
            $table->string('work_area_code')->nullable()->comment('add unique code'); // same code add to other tbale work process lists
            $table->string('addedby')->nullable()->comment('User ID who added this work item');
            $table->string('status')->nullable()->comment('Status of the work (e.g., pending, completed)');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_work_lists');
    }
};
