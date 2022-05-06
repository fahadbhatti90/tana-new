<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTanaSubcategoryInStgInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stg_inventory', function (Blueprint $table) {
            $table->string('tana_subcategory', 128)->after('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stg_inventory', function (Blueprint $table) {
            $table->dropColumn('tana_subcategory');
        });
    }
}
