<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAmsBiddingRuleCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_bidding_rule_cron', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_bidding_rule_id');

            $table->string('rule_ad_type',64);
            $table->string('look_back_period',64);
            $table->unsignedInteger('look_back_period_days');
            $table->string('frequency', 64);
            $table->unsignedInteger('frequency_days');

            $table->timestamp('last_run')->nullable();
            $table->timestamp('current_run')->nullable();
            $table->timestamp('next_run')->nullable();

            $table->unsignedTinyInteger('run_status')->default('0');//1(run) or 0(pending)
            $table->unsignedTinyInteger('check_rule_status')->default('0');//1(checked) or 0(not checked)
            $table->unsignedTinyInteger('rule_result')->default('0');//1(result found) or 0(result not found)
            $table->unsignedTinyInteger('email_send_status')->default('0');//1(send) or 0(not send)

            $table->unsignedTinyInteger('is_active')->default('1');//1(enable) or 0(disable)

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
        Schema::dropIfExists('tbl_ams_bidding_rule_cron');
    }
}
