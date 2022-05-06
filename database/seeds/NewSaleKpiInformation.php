<?php

use App\Model\Alerts\KpiInfo;
use Illuminate\Database\Seeder;

class NewSaleKpiInformation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'shippedCogsSummary',
            'report_table' => 'fact_sale_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'net_received',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'netReceivedSummary',
            'report_table' => 'fact_inventory_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'confirmation_rate',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'confirmationRateSummary',
            'report_table' => 'fact_po_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'yoy',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'yoySummary',
            'report_table' => 'fact_po_daily_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'newSaleTopAsinShippedCogs',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'newSaleTopAsinIncrease',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'daily',
            'report_graph' => 'newSaleTopAsinDecrease',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'shippedCogsSummary',
            'report_table' => 'fact_sale_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'net_received',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'netReceivedSummary',
            'report_table' => 'fact_inventory_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'confirmation_rate',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'confirmationRateSummary',
            'report_table' => 'fact_po_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'yoy',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'yoySummary',
            'report_table' => 'fact_po_weekly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'newSaleTopAsinShippedCogs',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'newSaleTopAsinIncrease',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'weekly',
            'report_graph' => 'newSaleTopAsinDecrease',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'shippedCogsSummary',
            'report_table' => 'fact_sale_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'net_received',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'netReceivedSummary',
            'report_table' => 'fact_inventory_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'confirmation_rate',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'confirmationRateSummary',
            'report_table' => 'fact_po_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'yoy',
            'sub_kpi_name' => 'None',
            'sub_kpi_value' => 0,
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'yoySummary',
            'report_table' => 'fact_po_monthly_summary',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'newSaleTopAsinShippedCogs',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'newSaleTopAsinIncrease',
            'report_table' => 'fact_sale_daily',
        ]);
        KpiInfo::create([
            'kpi_name' => 'shipped_cogs',
            'sub_kpi_name' => 'asin',
            'sub_kpi_value' => 'B008FC62Q6',
            'report_name' => 'newSale',
            'report_range' => 'monthly',
            'report_graph' => 'newSaleTopAsinDecrease',
            'report_table' => 'fact_sale_daily',
        ]);
    }
}
