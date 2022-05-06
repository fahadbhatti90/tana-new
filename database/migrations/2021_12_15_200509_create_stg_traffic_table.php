<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStgTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stg_traffic', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->mediumInteger('fk_vendor_id');
            $table->string('asin', 10);
            $table->string('product_title', 255)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->string('category', 128)->nullable();
            $table->string('model_number', 64)->nullable();
            $table->unsignedInteger('glance_views')->nullable();
            $table->decimal('glance_views_%_of total', 12, 4)->nullable();
            $table->decimal('glance_view_prior_period', 12, 4)->nullable();
            $table->decimal('glance_view_last_year', 12, 4)->nullable();
            $table->decimal('conversion_rate', 12, 4)->nullable();
            $table->decimal('conversion_rate_prior_period', 12, 4)->nullable();
            $table->decimal('conversion_rate_last_year', 12, 4)->nullable();
            $table->decimal('unique_visitors_prior_period', 12, 4)->nullable();
            $table->decimal('unique_visitors_last_year', 12, 4)->nullable();
            $table->decimal('fast_track_gv', 12, 4)->nullable();
            $table->decimal('fast_track_gv_prior_period', 12, 4)->nullable();
            $table->decimal('fast_track_gv_last_year', 12, 4)->nullable();
            $table->date('report_date')->default('1999-09-09');
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
        Schema::dropIfExists('stg_traffic');
    }
}
