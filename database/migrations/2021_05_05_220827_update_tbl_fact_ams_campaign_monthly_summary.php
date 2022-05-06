<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblFactAmsCampaignMonthlySummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('fact_ams_campaign_monthly_summary', function ($table) {
            $table->unsignedBigInteger('profile_id')->change();
            $table->string('entity_id', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('fact_ams_campaign_monthly_summary', function ($table) {
            $table->string('profile_id', 25)->change();
            $table->string('entity_id', 25)->change();
        });
    }
}
