<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactTrafficDailySummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_traffic_daily_summary', function (Blueprint $table){
            $table->Increments('row_id');
            $table->unsignedMediumInteger('fk_vendor_id');
            $table->unsignedInteger('glance_views')->nullable();
            $table->decimal('glance_view_prior_period', 12, 4)->nullable();
            $table->decimal('glance_view_last_year', 12, 4)->nullable();
            $table->decimal('conversion_rate', 12, 4)->nullable();
            $table->decimal('conversion_rate_prior_period', 12, 4)->nullable();
            $table->decimal('conversion_rate_last_year', 12, 4)->nullable();
            $table->decimal('fast_track_gv', 12, 4)->nullable();
            $table->decimal('fast_track_gv_prior_period', 12, 4)->nullable();
            $table->decimal('fast_track_gv_last_year', 12, 4)->nullable();
            $table->date('report_date')->default('1999-09-09');
            $table->timestamp("captured_at")->useCurrent();
            $table->unsignedInteger('date_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_traffic_daily_summary');
    }
}
