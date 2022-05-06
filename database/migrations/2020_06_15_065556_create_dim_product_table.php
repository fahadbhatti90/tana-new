<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dim_product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('asin',10);
            $table->string('product_title',255)->nullable();
            $table->string('model_no',64)->nullable();

            $table->index('asin','idx_asin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('dim_product');
    }
}
