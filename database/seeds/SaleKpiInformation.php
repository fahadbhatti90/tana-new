<?php

use App\Model\Alerts\KpiInfo;
use Illuminate\Database\Seeder;

class SaleKpiInformation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_units',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'category',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'saleTopAsinShippedCogs',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'saleTopAsinIncrease',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'daily',
            'report_graph'=> 'saleTopAsinDecrease',
            'report_table'=> 'fact_sale_daily',
        ]);

        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_units',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'category',
            'report_table'=> 'fact_sale_weekly',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'saleTopAsinShippedCogs',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'saleTopAsinIncrease',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'weekly',
            'report_graph'=> 'saleTopAsinDecrease',
            'report_table'=> 'fact_sale_daily',
        ]);

        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_units',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'summary',
            'report_table'=> 'fact_sale_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'None',
            'sub_kpi_value'=> 0,
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'category',
            'report_table'=> 'fact_sale_monthly',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'saleTopAsinShippedCogs',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'saleTopAsinIncrease',
            'report_table'=> 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name'=> 'shipped_cogs',
            'sub_kpi_name'=> 'asin',
            'sub_kpi_value'=> 'B008FC62Q6',
            'report_name'=> 'sale',
            'report_range'=> 'monthly',
            'report_graph'=> 'saleTopAsinDecrease',
            'report_table'=> 'fact_sale_daily',
        ]);
    }
}
