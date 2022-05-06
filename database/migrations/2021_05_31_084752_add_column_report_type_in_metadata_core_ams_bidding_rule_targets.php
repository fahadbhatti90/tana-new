<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReportTypeInMetadataCoreAmsBiddingRuleTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metadata_core_ams_bidding_rule_targets', function($table) {
            $table->string('report_type', 10)->after('profile_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metadata_core_ams_bidding_rule_targets', function($table) {
            $table->dropColumn('report_type');
        });
    }
}
