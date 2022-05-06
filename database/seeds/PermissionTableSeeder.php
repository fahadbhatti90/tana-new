<?php

use App\Model\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
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
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Permission::create(['permission_name'=>'canView']);
        Permission::create(['permission_name'=>'canAdd']);
        Permission::create(['permission_name'=>'canUpdate']);
        Permission::create(['permission_name'=>'canDelete']);
    }
}
