<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblColumnCoreAmsCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_ams_campaign', function ($table) {
            $table->unsignedBigInteger('profile_id')->change();
            $table->string('entity_id', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_ams_campaign', function (Blueprint $table) {
            $table->string('profile_id', 25)->change();
            $table->string('entity_id', 25)->change();
        });
    }
}
