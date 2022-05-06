<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveColumnDimAmsCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('dim_ams_campaign', function (Blueprint $table) {
            $table->renameColumn('c_id', 'row_id');
            $table->renameColumn('profile_name', 'campaign_report_type')->after('campaign_name')->nullable(false)->change();
            $table->unsignedBigInteger('campaign_id')->nullable(false)->change();
            $table->string('campaign_name', 255)->nullable(false)->change();
            $table->string('campaign_status', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('dim_ams_campaign', function ($table) {
            $table->renameColumn('row_id', 'c_id');
            $table->renameColumn('campaign_report_type', 'profile_name')->nullable()->change();
            $table->unsignedBigInteger('campaign_id')->nullable()->change();
            $table->string('campaign_name', 255)->nullable()->change();
            $table->string('campaign_status', 20)->nullable()->change();
        });
    }
}
