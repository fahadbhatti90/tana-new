<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRulesPresetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rules_preset', function (Blueprint $table) {
            $table->increments('id');
            $table->string('preset_name', 128);
            $table->string('look_back_period', 64);
            $table->string('look_back_period_days', 50);
            $table->string('frequency', 64);
            $table->text('metric');
            $table->text('condition');
            $table->text('integer_values');
            $table->string('and_or', 50);
            $table->text('then_clause');
            $table->string('bid_by_type', 50);
            $table->string('bid_by_value', 128);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_bidding_rules_preset');
    }
}
