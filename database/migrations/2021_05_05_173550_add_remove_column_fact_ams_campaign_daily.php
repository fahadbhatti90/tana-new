<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveColumnFactAmsCampaignDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('fact_ams_campaign_daily', function ($table) {
            $table->dropIndex('idx_asin_subcategory');
            $table->dropIndex('idx_vendor_date');

            $table->dropColumn('fk_vendor_id');
            $table->renameColumn('fk_profile_id', 'profile_id');
            $table->renameColumn('fk_entity_id', 'entity_id');
            $table->unsignedBigInteger('campaign_id')->nullable(false)->change();
            $table->decimal('campaign_budget', 10, 4)->nullable(false)->change();
            $table->unsignedDecimal('cost', 12, 4)->nullable(false)->change();
            $table->unsignedDecimal('campaign_sales', 12, 4)->nullable(false)->change();
            $table->unsignedDecimal('campaign_sales_sku', 12, 4)->nullable(false)->change();
            $table->unsignedDecimal('campaign_conversion', 12, 4)->nullable(false)->change();
            $table->unsignedDecimal('campaign_conversion_sku', 12, 4)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('fact_ams_campaign_daily', function ($table) {
            $table->integer('fk_vendor_id');
            $table->renameColumn('profile_id', 'fk_profile_id');
            $table->renameColumn('entity_id', 'fk_entity_id');
            $table->string('campaign_id', 25)->change();
            $table->decimal('campaign_budget', 10, 4)->nullable()->change();
            $table->unsignedDecimal('cost', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_sales', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_sales_sku', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_conversion', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_conversion_sku', 12, 4)->nullable()->change();
            $table->index(['fk_vendor_id'], 'idx_vendor_date');
            $table->index(['fk_profile_id'], 'idx_asin_subcategory');
        });
    }
}
