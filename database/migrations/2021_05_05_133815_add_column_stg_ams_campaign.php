<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStgAmsCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stg_ams_campaign', function ($table) {
            $table->dropIndex('idx_asin_subcategory');
            $table->dropIndex('idx_vendor_date');

            $table->dropColumn('fk_vendor_id');
            $table->dropColumn('profile_name');
            $table->renameColumn('fk_profile_id', 'profile_id');
            $table->renameColumn('fk_entity_id', 'entity_id');
            $table->unsignedBigInteger('campaign_id')->change();
            $table->string('campaign_name', 225)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable(false)->change();
            $table->string('campaign_status', 25)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable(false)->change();
            $table->decimal('campaign_budget', 10, 4)->nullable(false)->change();
            $table->string('campaign_type', 10)->nullable(false)->change();
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
        Schema::table('stg_ams_campaign', function (Blueprint $table) {
            $table->integer('fk_vendor_id');
            $table->string('profile_name', 120)->nullable();
            $table->renameColumn('profile_id', 'fk_profile_id');
            $table->renameColumn('entity_id', 'fk_entity_id');
            $table->string('campaign_id', 25)->change();

            $table->string('campaign_status', 25)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->nullable()->change();
            $table->decimal('campaign_budget', 10, 4)->nullable()->change();
            $table->string('campaign_type', 10)->nullable()->change();
            $table->unsignedDecimal('cost', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_sales', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_sales_sku', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_conversion', 12, 4)->nullable()->change();
            $table->unsignedDecimal('campaign_conversion_sku', 12, 4)->nullable()->change();
            $table->index(['fk_vendor_id'], 'idx_vendor_date');
            $table->index(['fk_profile_id', 'campaign_id'], 'idx_asin_subcategory');
        });
    }
}
