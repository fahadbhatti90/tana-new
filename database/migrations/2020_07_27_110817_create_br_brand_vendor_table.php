<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrBrandVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('br_brand_vendor', function (Blueprint $table) {
            $table->Increments('row_id');
            $table->unsignedSmallInteger('fk_brand_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedSmallInteger('rdm_brand_id');
            $table->unsignedSmallInteger('rdm_vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('br_brand_vendor');
    }
}
