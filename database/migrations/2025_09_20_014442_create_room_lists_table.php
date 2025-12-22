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
         Schema::create('room_lists', function (Blueprint $table) {
           $table->id();
           $table->string('site_id')->nullable()->comment('Create site id for ref site project');
           $table->integer('room_qty')->nullable()->comment('Number of rooms for the project');
           $table->json('room_details')->nullable()->comment('Array of room names like bedroom, master bedroom, etc.');
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
        Schema::dropIfExists('room_lists');
    }
};
