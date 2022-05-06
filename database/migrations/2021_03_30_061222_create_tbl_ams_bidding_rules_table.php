<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_pre_set_rule_id');
            $table->string('rule_name',128);

            $table->text('profile_id');
            $table->string('rule_ad_type',64);
            $table->string('rule_select_type',64);
            $table->text('rule_select_type_value');

            $table->string('look_back_period',64);
            $table->string('look_back_period_days',50);
            $table->string('frequency', 64);

            $table->text('metric');
            $table->text('condition');
            $table->text('integer_values');

            $table->string('and_or', 50);

            $table->text('then_clause');
            $table->string('bid_by_type', 50);
            $table->string('bid_by_value', 128);

            $table->text('cc_emails')->nullable();

            $table->unsignedTinyInteger('is_active')->default('1');

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
        Schema::dropIfExists('tbl_ams_bidding_rules');
    }
}
