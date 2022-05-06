<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMgmtBrandVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_brand_vendors', function (Blueprint $table) {
            $table->smallIncrements('row_id');
            $table->unsignedSmallInteger('fk_brand_id');
            $table->unsignedSmallInteger('fk_vendor_id');
            $table->foreign('fk_brand_id')->references('brand_id')->on('mgmt_brand')->onDelete('cascade');
            $table->foreign('fk_vendor_id')->references('vendor_id')->on('mgmt_vendor')->onDelete('cascade');
            $table->unique(['fk_brand_id', 'fk_vendor_id'],'unique_idx_mgmt_brand_vendors');
            $table->index('fk_brand_id');
            $table->index('fk_vendor_id');
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
        Schema::dropIfExists('mgmt_brand_vendors');
    }
}
