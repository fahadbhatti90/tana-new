<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsAsinSpReportIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_asin_sp_report_id', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_access_token');
            $table->unsignedBigInteger('fk_profile_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('report_id');
            $table->string('record_type', 50);
            $table->string('status');
            $table->string('status_details', 50);
            $table->string('report_date', 20);
            $table->unsignedTinyInteger('is_done')->default(0);
            $table->date('creation_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_asin_sp_report_id');
    }
}
