<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSponsoredDisplayAudiencesTargetingSixtyDaysColumnInAmsProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_profiles', function($table) {
            $table->string('sponsored_display_audiences_targeting_sixty_days', 50)->after('sponsored_display_product_targeting_sixty_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_profiles', function($table) {
            $table->dropColumn('sponsored_display_audiences_targeting_sixty_days');
        });
    }
}
