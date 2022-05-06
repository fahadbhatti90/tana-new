<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dim_brand', function (Blueprint $table) {
            $table->smallIncrements('brand_id');
            $table->unsignedSmallInteger('rdm_brand_id')->nullable();
            $table->string('brand_name',64)->nullable();
            $table->unsignedTinyInteger('is_active')->default('1');
            $table->timestamps();

            $table->index('rdm_brand_id','idx_brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('dim_brand');
    }
}
