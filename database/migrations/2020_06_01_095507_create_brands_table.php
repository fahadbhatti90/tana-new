<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mgmt_brand', function (Blueprint $table) {
            $table->smallIncrements('brand_id');
            $table->string('brand_name', 64)->unique();
            $table->unsignedTinyInteger('is_active')->default('1');
            $table->index('brand_id');
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
        Schema::dropIfExists('mgmt_brand');
    }
}
