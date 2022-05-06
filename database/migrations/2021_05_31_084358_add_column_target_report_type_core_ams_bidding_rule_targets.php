<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTargetReportTypeCoreAmsBiddingRuleTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_ams_bidding_rule_targets', function($table) {
            $table->string('target_report_type', 50)->after('targeting_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_ams_bidding_rule_targets', function($table) {
            $table->dropColumn('target_report_type');
        });
    }
}
