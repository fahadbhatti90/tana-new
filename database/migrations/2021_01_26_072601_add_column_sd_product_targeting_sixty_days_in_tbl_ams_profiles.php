<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSdProductTargetingSixtyDaysInTblAmsProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_profiles', function($table) {
            $table->unsignedTinyInteger('sponsored_display_product_targeting_sixty_days')->after('sponsored_display_productads_sixty_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_profiles', function (Blueprint $table) {
            $table->dropColumn('sponsored_display_product_targeting_sixty_days');
        });
    }
}
