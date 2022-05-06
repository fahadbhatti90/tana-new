<?php

use App\Model\Module;
use App\Model\Permission;
use App\Model\RoleModulePermission;
use Illuminate\Database\Seeder;
use App\Model\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mgmt_user_role')->truncate();
        DB::table('mgmt_role_module_permission')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Role::create(['role_name'=>'Super Admin']);
        Role::create(['role_name'=>'Admin']);
        Role::create(['role_name'=>'User']);

        foreach(Module::all('module_id') as $module){
            foreach(Permission::all('permission_id') as $permission){
                $form_data = array(
                    'fk_role_id' => 1,
                    'fk_permission_id' => $permission->permission_id,
                    'fk_module_id' => $module->module_id,
                );
                RoleModulePermission::create($form_data);
            }
        }

        $module = array(3,4,5,6,7,9,10);
        for($i= 0;$i < sizeof($module);$i++) {
            foreach (Permission::all('permission_id') as $permission) {
                $form_data = array(
                    'fk_role_id' => 2,
                    'fk_permission_id' => $permission->permission_id,
                    'fk_module_id' => $module[$i],
                );
                RoleModulePermission::create($form_data);
            }
        }

        $module = array(4,5);
        for($i= 0;$i < sizeof($module);$i++) {
            foreach (Permission::all('permission_id') as $permission) {
                $form_data = array(
                    'fk_role_id' => 3,
                    'fk_permission_id' => $permission->permission_id,
                    'fk_module_id' => $module[$i],
                );
                RoleModulePermission::create($form_data);
            }
        }
    }
}
