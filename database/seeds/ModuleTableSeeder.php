<?php

use App\Model\Module;
use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mgmt_role_module_permission')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Module::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Module::create(['module_name'=>'Super Admin']);
        Module::create(['module_name'=>'Admin']);
        Module::create(['module_name'=>'User']);
        Module::create(['module_name'=>'Brand']);
        Module::create(['module_name'=>'Vendor']);
        Module::create(['module_name'=>'Brand Association']);
        Module::create(['module_name'=>'Vendor Association']);
        Module::create(['module_name'=>'Report Uploading']);
        Module::create(['module_name'=>'Category Uploading']);
        Module::create(['module_name'=>'PTP Uploading']);
    }
}
