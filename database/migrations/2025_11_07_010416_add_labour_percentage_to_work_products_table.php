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
            $table->string('labour_percentage')->nullable()->after('rate')->comment('Labour percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_products', function (Blueprint $table) {
            $table->dropColumn('labour_percentage');
        });
    }
};
