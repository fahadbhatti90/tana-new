<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ams_cron_jobs', function (Blueprint $table) {
            $table->increments('cron_id');
            $table->string('cron_name',128);
            $table->string('cron_slag',128);
            $table->string('cron_type',64);
            $table->time('cron_time')->default('00:00');
            $table->string('cron_status',16)->default('disable');//enable or disable
            $table->timestamp('last_run')->nullable();
            $table->timestamp('modified_date')->useCurrent();
            $table->unsignedTinyInteger('run_status')->default('0');
            $table->timestamp('next_run')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ams_cron_jobs');
    }
}
