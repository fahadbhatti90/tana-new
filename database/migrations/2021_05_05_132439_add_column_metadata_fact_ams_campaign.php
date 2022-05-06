<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMetadataFactAmsCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metadata_fact_ams_campaign', function ($table) {
            $table->dropColumn('fk_vendor_id');
            $table->unsignedBigInteger('profile_id')->after('row_id');
            $table->string('report_type', 10)->after('profile_name');
            $table->dropIndex('idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metadata_fact_ams_campaign', function (Blueprint $table) {
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->dropColumn('profile_id');
            $table->dropColumn('report_type');
        });
    }
}
