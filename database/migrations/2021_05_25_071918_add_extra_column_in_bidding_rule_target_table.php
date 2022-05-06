<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnInBiddingRuleTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_bidding_rule_target', function($table) {
            $table->string('expression', 255)->after('bid')->nullable();
            $table->string('expressions_type', 255)->after('expression')->nullable();
            $table->string('expressions_value', 255)->after('expressions_type')->nullable();
            $table->string('resolved_expression_type', 255)->after('expressions_value')->nullable();
            $table->string('resolved_expression_value', 255)->after('resolved_expression_type')->nullable();
            $table->string('serving_status', 255)->after('resolved_expression_value')->nullable();
            $table->string('creation_date', 255)->after('serving_status')->nullable();
            $table->string('last_updated_date', 255)->after('creation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_bidding_rule_target', function($table) {
            $table->dropColumn('expression');
            $table->dropColumn('expressions_type');
            $table->dropColumn('expressions_value');
            $table->dropColumn('resolved_expression_type');
            $table->dropColumn('resolved_expression_value');
            $table->dropColumn('serving_status');
            $table->dropColumn('creation_date');
            $table->dropColumn('last_updated_date');
        });
    }
}
