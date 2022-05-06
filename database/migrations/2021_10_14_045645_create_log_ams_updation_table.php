<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAmsUpdationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_ams_updation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('profile_id', 150);
            $table->string('report', 150);
            $table->string('report_type', 150);
            $table->tinyInteger('log_update')->default(0);
            $table->tinyInteger('core_update')->default(0);
            $table->tinyInteger('fact_daily_update')->default(0);
            $table->tinyInteger('fact_weekly_update')->default(0);
            $table->tinyInteger('fact_monthly_update')->default(0);
            $table->date('reported_date');
            $table->timestamp('updation_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_ams_updation');
    }
}
