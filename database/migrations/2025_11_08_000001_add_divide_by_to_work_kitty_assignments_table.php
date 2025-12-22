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
            $table->integer('divide_by')->default(1)->after('work_kitty_id')->comment('Divide work days by this value (1 or 2)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_kitty_assignments', function (Blueprint $table) {
            $table->dropColumn('divide_by');
        });
    }
};

