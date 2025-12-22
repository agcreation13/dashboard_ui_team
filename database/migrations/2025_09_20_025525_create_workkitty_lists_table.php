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
        Schema::create('workkitty_lists', function (Blueprint $table) {
            $table->id();
        
            $table->string('site_id')->nullable()->comment('Reference to site/project');
            $table->string('room_id')->nullable()->comment('Reference to the room (room_lists.id)');
            $table->string('workkitty_title')->nullable()->comment('Work kitty title, e.g., "Kitty 1"');
        
            $table->json('workkitty_details')->nullable()->comment('Array of room_work_lists IDs associated with this workkitty');
        
            $table->string('workkitty_value')->nullable()->comment('Sum of p1_percentage_value + p2_percentage_value + p3_percentage_value from the room_work_lists listed in workkitty_details');
        
            $table->string('addedby')->nullable()->comment('User ID of the person who added this work kitty');
            $table->string('status')->nullable()->comment('Workkitty Status');
        
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workkitty_lists');
    }
};
