<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactAmsKeywordSearchTermDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('fact_ams_keyword_search_term_daily', function (Blueprint $table) {
            $table->bigIncrements('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('entity_id', 50);

            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('ad_group_id');
            $table->unsignedBigInteger('keyword_id');
            $table->string('keyword_report_type',4)->nullable();
            $table->string('match_type', 20)->nullable();

            $table->mediumInteger('impressions')->default(0);
            $table->mediumInteger('clicks')->default(0);
            $table->decimal('cost', 16, 4)->default(0.0000);

            $table->mediumInteger('conversions')->nullable();
            $table->mediumInteger('conversions_same_sku')->nullable();

            $table->mediumInteger('units_ordered')->nullable();
            $table->mediumInteger('units_ordered_same_sku')->nullable();

            $table->decimal('sales', 16, 4)->nullable();
            $table->decimal('sales_same_sku', 16, 4)->nullable();

            $table->date('reported_date')->default("1999-09-09");
            $table->integer('date_key')->default("19990909");
            $table->timestamp('captured_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('fact_ams_keyword_search_term_daily');
    }
}
