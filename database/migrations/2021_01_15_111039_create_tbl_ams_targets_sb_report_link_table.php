<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsTargetsSbReportLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_targets_sb_report_link', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('profile_id', 50);
            $table->string('report_id');
            $table->string('status', 50);
            $table->string('status_details', 100);
            $table->string('location');
            $table->string('file_size', 50);
            $table->string('report_date', 20);
            $table->date('creation_date');
            $table->tinyInteger('is_done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_targets_sb_report_link');
    }
}
