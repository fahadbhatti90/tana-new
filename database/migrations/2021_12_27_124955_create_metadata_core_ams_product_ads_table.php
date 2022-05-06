<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataCoreAmsProductAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata_core_ams_product_ads', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('profile_name', 120);
            $table->string('report_type', 10)->nullable();
            $table->date('max_reported_date')->default('1999-09-09');
            $table->dateTime('inserted_at');
            $table->index(['max_reported_date'], 'idx_vendor_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata_core_ams_product_ads');
    }
}
