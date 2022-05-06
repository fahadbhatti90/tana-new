<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTblAmsBiddingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_bidding_rules', function ($table) {
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
        Schema::table('tbl_ams_bidding_rules', function (Blueprint $table) {
            $table->dropColumn('bid_cpc_type');
        });
    }
}
