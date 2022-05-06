<?php

use App\Model\Module;
use App\Model\Permission;
use App\Model\RoleModulePermission;
use Illuminate\Database\Seeder;
use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class BiddingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = Module::create(['module_name'=>'Bidding Rule']);
        foreach (Permission::all('permission_id') as $permission) {
            $form_data = array(
                'fk_role_id' => 1,
                'fk_permission_id' => $permission->permission_id,
                'fk_module_id' => $module->module_id,
            );
            RoleModulePermission::create($form_data);
        }
    }
}
