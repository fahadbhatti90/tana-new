<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSearchTermSpSixtyDaysInTblAmsProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_profiles', function (Blueprint $table) {
            $table->string('search_term_sp_sixty_days', 50)->after('keyword_sp_sixty_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_profiles', function (Blueprint $table) {
            $table->dropColumn('search_term_sp_sixty_days');
        });
    }
}
