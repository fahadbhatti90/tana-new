<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataLogAmsTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_log_ams_targets', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('profile_name', 120);
            $table->string('report_type', 10);
            $table->date('data_max_date')->default('1999-09-09');
            $table->integer('id_max_date')->default('19990909');
            $table->integer('link_max_date')->default('19990909');
            $table->datetime('inserted_at');

            $table->index(['data_max_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_log_ams_targets');
    }
}
