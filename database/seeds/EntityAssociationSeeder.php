<?php

use App\Model\Module;
use App\Model\Permission;
use App\Model\RoleModulePermission;
use Illuminate\Database\Seeder;

class EntityAssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::create(['module_name'=>'Entity Association']);

        $module = array(13);
        for($i= 0;$i < sizeof($module);$i++) {
            foreach (Permission::all('permission_id') as $permission) {
                $form_data = array(
                    'fk_role_id' => 1,
                    'fk_permission_id' => $permission->permission_id,
                    'fk_module_id' => $module[$i],
                );
                RoleModulePermission::create($form_data);
            }
        }

    }
}
