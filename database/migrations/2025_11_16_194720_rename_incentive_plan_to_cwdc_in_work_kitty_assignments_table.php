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
        Schema::table('work_kitty_assignments', function (Blueprint $table) {
            $table->renameColumn('incentive_plan', 'cwdc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_kitty_assignments', function (Blueprint $table) {
            $table->renameColumn('cwdc', 'incentive_plan');
        });
    }
};
