<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblAmsCampaignSdReportDataTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('tbl_ams_campaign_sd_report_data', function (Blueprint $table) {
                $table->string('view_attributed_conversions_14d', 50)->default(0)->after('attributed_sales_30d_same_sku');
                $table->string('view_attributed_detail_page_view_14d', 50)->default(0)->after('attributed_sales_30d_same_sku');
                $table->string('view_attributed_sales_14d', 50)->default(0)->after('attributed_sales_30d_same_sku');
                $table->string('view_attributed_units_ordered_14d', 50)->default(0)->after('attributed_sales_30d_same_sku');
                $table->string('view_impressions', 50)->default(0)->after('attributed_sales_30d_same_sku');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
