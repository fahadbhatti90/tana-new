<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreAmsBiddingRuleKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_ams_bidding_rule_keyword', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('entity_id', 50);
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('keyword_id');
            $table->string('ad_group_id', 50)->nullable();

            $table->string('campaign_name', 255)->nullable();
            $table->string('ad_group_name', 255)->nullable();
            $table->string('keyword_report_type',50)->nullable();
            $table->string('keyword_text', 255)->nullable();
            $table->string('match_type', 50)->nullable();

            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedDecimal('cost', 16, 4)->default(0.0000);

            $table->unsignedDecimal('keyword_sales', 16, 4)->default(0.0000);
            $table->decimal('keyword_sales_sku', 16, 4)->nullable();

            $table->integer('keyword_units')->nullable();
            $table->integer('keyword_units_sku')->nullable();

            $table->integer('keyword_conversion')->nullable();
            $table->integer('keyword_conversion_sku')->nullable();

            $table->date('reported_date')->default("1999-09-09");
            $table->integer('date_key')->default("19990909");
            $table->timestamp('captured_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_ams_bidding_rule_keyword');
    }
}
