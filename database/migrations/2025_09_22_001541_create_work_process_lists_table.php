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
        Schema::create('work_process_lists', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->nullable()->comment('Create site id for ref site project');
            $table->integer('room_id')->nullable()->comment('Number of rooms for the project');
            $table->integer('room_sr_no')->nullable()->comment('Array of room names like bedroom, master bedroom, etc.');
            $table->json('room_work_entity')->nullable()->comment('Array of room work details, possibly including name, type, etc.');
            $table->string('total_value')->nullable()->comment('RFT+SFT sadd total RFT + SFT value'); // add to other tbale work process lists
            $table->string('labour_percentage')->nullable()->comment('Labour percentage'); // add to other tbale work process lists
            $table->string('labour_value')->nullable()->comment('Labour value divided proportion as for value come working day');
            $table->string('work_day_count')->nullable()->comment('Estimated number of workdays');
            $table->string('work_area_code')->nullable()->comment('add unique code'); // same code add form work area room work lists
            $table->string('addedby')->nullable()->comment('User ID of the person who added the room list');
            $table->string('status')->nullable()->comment('Room Status');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_process_lists');
    }
};
