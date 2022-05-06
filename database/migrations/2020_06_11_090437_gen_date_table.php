<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gen_date', function (Blueprint $table) {

            $table->increments('date_key');
            $table->date('full_date')->is_nullable()->default('1999-09-09');
            $table->char('date_name', 11);
            $table->char('date_name_us', 11);
            $table->char('date_name_eu', 11);
            $table->unsignedTinyInteger('day_of_week');
            $table->char('day_name_of_week', 10);
            $table->unsignedTinyInteger('day_of_month');
            $table->unsignedSmallInteger('day_of_year');
            $table->char('weekday_weekend', 10);
            $table->unsignedTinyInteger('week_of_month');
            $table->string('week_name_of_month', 10)->nullable();
            $table->unsignedTinyInteger('week_of_year');
            $table->char('week_name_of_year', 8);
            $table->char('month_name', 10);
            $table->unsignedTinyInteger('month_of_year');
            $table->char('is_last_day_of_month', 1);
            $table->unsignedTinyInteger('calendar_quarter');
            $table->unsignedSmallInteger('calendar_year');
            $table->char('calendar_year_month', 10);
            $table->char('calendar_year_qtr', 10);
            $table->unsignedTinyInteger('fiscal_month_of_year');
            $table->unsignedTinyInteger('fiscal_quarter');
            $table->integer('fiscal_year');
            $table->char('fiscal_year_month', 10);
            $table->char("fiscal_year_qtr", 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gen_date');
    }
}
