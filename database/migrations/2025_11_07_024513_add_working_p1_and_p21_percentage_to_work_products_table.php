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
        Schema::table('work_products', function (Blueprint $table) {
            $table->string('working_p1_percentage')->nullable()->after('labour_percentage')->comment('Working P1 percentage');
            $table->string('working_p2_percentage')->nullable()->after('working_p1_percentage')->comment('Working P2 percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_products', function (Blueprint $table) {
            $table->dropColumn(['working_p1_percentage', 'working_p2_percentage']);
        });
    }
};
