<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrcTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('src_traffic', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->string('fk_vendor_id', 4);
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_number', 128)->nullable();
            $table->string('glance_views', 10)->nullable();
            $table->string('glance_views_%_of total', 10)->nullable();
            $table->string('glance_view_prior_period', 10)->nullable();
            $table->string('glance_view_last_year', 10)->nullable();
            $table->string('conversion_rate', 10)->nullable();
            $table->string('conversion_rate_prior_period', 10)->nullable();
            $table->string('conversion_rate_last_year', 10)->nullable();
            $table->string('unique_visitors_prior_period', 10)->nullable();
            $table->string('unique_visitors_last_year', 10)->nullable();
            $table->string('fast_track_gv', 10)->nullable();
            $table->string('fast_track_gv_prior_period', 10)->nullable();
            $table->string('fast_track_gv_last_year', 10)->nullable();
            $table->date('report_date')->default('1999-09-09');
            $table->timestamp('captured_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('src_traffic');
    }
}
