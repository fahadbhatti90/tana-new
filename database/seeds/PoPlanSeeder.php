<?php

use App\Model\ExecutiveDashboard\POPlan;
use Illuminate\Database\Seeder;

class PoPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mgmt_po_plan')->truncate();
        POPlan::create([
            'name' => 'po_value',
            'value' => 0
        ]);
        POPlan::create([
            'name' => 'po_unit',
            'value' => 0
        ]);
    }
}
