<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTblAmsCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_campaigns', function($table) {
            $table->string('cost_type', 50)->after('state')->nullable();
            $table->string('tactic', 50)->after('cost_type')->nullable();
            $table->string('delivery_profile', 50)->after('tactic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_campaigns', function($table) {
            $table->dropColumn('cost_type');
            $table->dropColumn('tactic');
            $table->dropColumn('delivery_profile');
        });
    }
}
