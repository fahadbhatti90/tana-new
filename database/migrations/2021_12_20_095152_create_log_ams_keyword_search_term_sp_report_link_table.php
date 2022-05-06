<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAmsKeywordSearchTermSpReportLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_ams_keyword_search_term_sp_report_link', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('profile_id', 50);
            $table->string('report_id', 191);
            $table->string('status', 50);
            $table->string('status_details', 100);
            $table->string('location', 191);
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
        Schema::dropIfExists('log_ams_keyword_search_term_sp_report_link');
    }
}
