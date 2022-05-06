<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModuleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);

        /*
         * create users
         */
        $this->call(UserTableSeeder::class);

        /*
         * create dummy KPI Information for sales in KpiInfo table
         */
        $this->call(SaleKpiInformation::class);

        /*
         * Add by default value of po_value and po_unit in mgmt_po_plan table
         */
        $this->call(PoPlanSeeder::class);

        /*
         * create Data Entry Operator Role, Module and users,
         * also assign permission of operator to Super Admin
         */
        $this->call(OperatorSeeder::class);

        /*
         * create dummy KPI Information for new sales in KpiInfo table
         */
        $this->call(NewSaleKpiInformation::class);

        /*
         * create dummy AMS Cron Job
         */
        $this->call(AMSCronJobSeeder::class);

        /*
         * create Bidding Rule Module,
         * also assign permission of Bidding to Super Admin
         */
        $this->call(BiddingRuleSeeder::class);


        /*
         * create Bidding Rule User Module,
         * also assign permission of Bidding to super admin and admin
         */
        $this->call(UserModuleBiddingRuleTableSeeder::class);

        /*
         * create dummy AMS Cron Job For Audience Target SD
         */
        $this->call(AMSAudiencesCronJobSeed::class);

        /*
         * create Entity Association Role Module
         */
        $this->call(EntityAssociationSeeder::class);

        /*
         * create dummy AMS Cron Job For Search Term SP
         */
        $this->call(AMSSearchTermCronJobSeed::class);

        /*
         * create Bussiness Review User Module,
         * also assign permission of Bussiness Review to super admin and admin
         */
        $this->call(UserModuleBusinessReviewTableSeeder::class);
        /*
         * create Sales User Module,
         * also assign permission of Sales to super admin and admin
         */
        $this->call(UserModuleSalesReportTableSeeder::class);
        /*
         * create ConformedPO User Module,
         * also assign permission of ConformedPO to super admin and admin
         */
        $this->call(UserModuleConformedPOReportTableSeeder::class);
        /*
         * create Dashboared User Module,
         * also assign permission of Dashboared to super admin and admin
         */
        $this->call(UserModuleExecutiveDashboardReportTableSeeder::class);
    }
}
