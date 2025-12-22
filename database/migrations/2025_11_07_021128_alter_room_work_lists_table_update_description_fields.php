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
        // Check if table exists, if not create it with new structure
        if (!Schema::hasTable('room_work_lists')) {
            Schema::create('room_work_lists', function (Blueprint $table) {
                $table->id();
                $table->string('site_id')->nullable()->comment('Reference to site/project');
                $table->string('room_id')->nullable()->comment('Reference to the room room_lists id');
                $table->string('room_sr_no')->nullable()->comment('Room serial number form room_details under sr/id');
                $table->string('work_area_name')->nullable()->comment('Work area name');
                $table->string('work_product_name')->nullable()->comment('Work product name');
                $table->integer('work_area_quantity_SFT')->nullable()->comment('Quantity for the work area');
                $table->integer('rate_SFT')->nullable()->comment('Rate per unit');
                $table->integer('work_area_quantity_RFT')->nullable()->comment('Quantity for the work area');
                $table->integer('rate_RFT')->nullable()->comment('Rate per unit');
                $table->string('total_RFT_SFTvalue')->nullable()->comment('Total RFT + SFT value');
                $table->integer('labour_percentage')->nullable()->comment('Labour percentage');
                $table->integer('labour_percentage_value')->nullable()->comment('Calculated value from labour percentage');
                $table->integer('work_day_count')->nullable()->comment('Estimated number of workdays');
                $table->integer('p1_percentage')->nullable()->comment('P1 percentage');
                $table->integer('p2_percentage')->nullable()->comment('P2 percentage');
                $table->integer('p1_percentage_value')->nullable()->comment('P1 calculated value');
                $table->integer('p2_percentage_value')->nullable()->comment('P2 calculated value');
                $table->text('p1_description')->nullable()->comment('P1 work description');
                $table->text('p2_description')->nullable()->comment('P2 work description');
                $table->string('addedby')->nullable()->comment('User ID who added this work item');
                $table->string('status')->nullable()->comment('Status of the work (e.g., pending, completed)');
                $table->timestamps();
            });
        } else {
            // Table exists, alter it
            Schema::table('room_work_lists', function (Blueprint $table) {
                // Add new columns for SFT and RFT
                if (!Schema::hasColumn('room_work_lists', 'work_area_quantity_SFT')) {
                    $table->integer('work_area_quantity_SFT')->nullable()->after('work_product_name')->comment('Quantity for the work area SFT');
                }
                if (!Schema::hasColumn('room_work_lists', 'rate_SFT')) {
                    $table->integer('rate_SFT')->nullable()->after('work_area_quantity_SFT')->comment('Rate per unit SFT');
                }
                if (!Schema::hasColumn('room_work_lists', 'work_area_quantity_RFT')) {
                    $table->integer('work_area_quantity_RFT')->nullable()->after('rate_SFT')->comment('Quantity for the work area RFT');
                }
                if (!Schema::hasColumn('room_work_lists', 'rate_RFT')) {
                    $table->integer('rate_RFT')->nullable()->after('work_area_quantity_RFT')->comment('Rate per unit RFT');
                }
                if (!Schema::hasColumn('room_work_lists', 'total_RFT_SFTvalue')) {
                    $table->string('total_RFT_SFTvalue')->nullable()->after('rate_RFT')->comment('Total RFT + SFT value');
                }
                
                // Add description fields if they don't exist, or alter if they exist as integer
                if (!Schema::hasColumn('room_work_lists', 'p1_description')) {
                    $table->text('p1_description')->nullable()->after('p2_percentage_value')->comment('P1 work description');
                } else {
                    $table->text('p1_description')->nullable()->change();
                }
                if (!Schema::hasColumn('room_work_lists', 'p2_description')) {
                    $table->text('p2_description')->nullable()->after('p1_description')->comment('P2 work description');
                } else {
                    $table->text('p2_description')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_work_lists', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'work_area_quantity_SFT',
                'rate_SFT',
                'work_area_quantity_RFT',
                'rate_RFT',
                'total_RFT_SFTvalue',
                'p1_description',
                'p2_description'
            ]);
        });
    }
};
