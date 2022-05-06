<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dim_vendor', function (Blueprint $table) {
            $table->mediumIncrements('vendor_id');
            $table->unsignedMediumInteger('rdm_vendor_id')->nullable();
            $table->string('vendor_name',64)->nullable();
            $table->string('domain',3)->nullable();
            $table->string('tier',9)->nullable();
            $table->unsignedTinyInteger('is_active')->default('1');
            $table->timestamps();

            $table->index('vendor_name','idx_vendor_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('dim_vendor');
    }
}
