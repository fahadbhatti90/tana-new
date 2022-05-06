<?php

use App\Model\Module;
use App\Model\Permission;
use App\Model\Role;
use App\Model\UserModule;
use App\Model\UserModulePermission;
use Illuminate\Database\Seeder;

class UserModuleExecutiveDashboardReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = UserModule::create(['module_name' => 'Dashboard']);

        // by default permission for super admin
        $roles = Role::whereIN('role_id', array(1, 2))->get();
        foreach ($roles as $role) {
            foreach ($role->users()->get() as $user) {
                foreach (Permission::all('permission_id') as $permission) {
                    $form_data = array(
                        'fk_user_id' => $user->user_id,
                        'fk_permission_id' => $permission->permission_id,
                        'fk_module_id' => $module->module_id,
                    );
                    UserModulePermission::create($form_data);
                }
            }
        }
    }
}
