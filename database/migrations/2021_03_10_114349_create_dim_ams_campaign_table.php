<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimAmsCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dim_ams_campaign', function (Blueprint $table) {
            $table->increments('c_id');
            $table->string('campaign_id', 25)->nullable();
            $table->string('campaign_name', 255)->nullable();
            $table->string('profile_name', 120)->nullable();
            $table->string('campaign_status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('dim_ams_campaign');
    }
}
