<?php

use App\Model\Module;
use App\Model\Permission;
use App\Model\RoleModulePermission;
use Illuminate\Database\Seeder;
use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleOperator = Role::create(['role_name' =>'Data Entry Operator']);
        Module::create(['module_name'=>'Operator']);

        $operator = User::create(['username' => 'Haris Zaman', 'email' => 'haris.zaman@diginc.pk','password' => Hash::make('123456789'),]);
        $operator->roles()->attach($roleOperator);
        $operator = User::create(['username' => 'Salman Khan', 'email' => 'salman.khan@diginc.pk','password' => Hash::make('123456789'),]);
        $operator->roles()->attach($roleOperator);

        $module = array(11);
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
