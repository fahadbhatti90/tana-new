<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dim_po', function (Blueprint $table) {
            $table->mediumIncrements('po_id');
            $table->string('po', 10)->nullable();
            $table->string('vendor', 12);
            $table->string('ship_to_location', 64)->nullable();
            $table->string('external_id', 24)->nullable();
            $table->string('availability', 48)->nullable();
            $table->string('backordered', 12)->nullable();
            $table->string('window_type', 40)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('status', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('dim_po');
    }
}
