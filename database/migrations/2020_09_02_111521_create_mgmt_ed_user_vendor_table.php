<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtEdUserVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_ed_user_vendor', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedSmallInteger('fk_user_id');
            $table->unsignedSmallInteger('fk_vendor_id_ed');
            $table->unsignedSmallInteger('fk_vendor1_id_confirm_po');
            $table->unsignedSmallInteger('fk_vendor2_id_confirm_po');
            $table->unsignedSmallInteger('fk_vendor3_id_confirm_po');
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
        Schema::dropIfExists('mgmt_ed_user_vendor');
    }
}
