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
        Schema::create('labour_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Role name');
            $table->string('parent_id')->default('0')->comment('Parent role ID, if applicable');
            $table->string('addedBy')->default('1')->comment('Added by (user name)');
            $table->enum('status', ['active', 'deactivate'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour_roles');
    }
};
