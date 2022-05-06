<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTblAmsBiddingRulesPresetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_bidding_rules_preset', function ($table) {
            $table->string('bid_cpc_type', 5)->after('and_or');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_bidding_rules_preset', function (Blueprint $table) {
            $table->dropColumn('bid_cpc_type');
        });
    }
}
