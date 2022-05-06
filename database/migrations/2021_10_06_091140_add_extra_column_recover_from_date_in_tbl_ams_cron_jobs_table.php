<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnRecoverFromDateInTblAmsCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ams_cron_jobs', function($table) {
            $table->timestamp('recover_back_from_date')->after('recover')->useCurrent();
            $table->unsignedTinyInteger('is_running')->after('recover_back_from_date')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ams_cron_jobs', function($table) {
            $table->dropColumn('recover_back_from_date');
            $table->dropColumn('is_running');
        });
    }
}
