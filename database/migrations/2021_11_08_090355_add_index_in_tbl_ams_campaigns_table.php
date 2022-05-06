<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexInTblAmsCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_campaigns', function (Blueprint $table) {
            $table->index(['campaign_id'], 'idx_campaign_id');
            $table->index(['profile_id', 'campaign_id', 'type'], 'idx_profile_campaign_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_campaigns', function (Blueprint $table) {
            $table->dropIndex('idx_campaign_id');
            $table->dropIndex('idx_profile_campaign_type');
        });
    }
}
