<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataFactAmsKeywordSearchTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_fact_ams_keyword_search_term', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('profile_name', 120);
            $table->string('report_type', 10)->nullable();
            $table->date('daily_max_date')->default('1999-09-09');
            $table->date('weekly_max_date')->default('1999-09-09');
            $table->date('monthly_max_date')->default('1999-09-09');
            $table->dateTime('inserted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_fact_ams_keyword_search_term');
    }
}
