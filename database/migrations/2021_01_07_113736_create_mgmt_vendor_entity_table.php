<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtVendorEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_vendor_entity', function (Blueprint $table) {
            $table->smallIncrements('row_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->unsignedSmallInteger('fk_profile_id');
            $table->string('entity_id', 64);
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
        Schema::dropIfExists('mgmt_vendor_entity');
    }
}
