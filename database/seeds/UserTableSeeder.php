<?php

use App\Model\Profile;
use Illuminate\Database\Seeder;
use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mgmt_user_role')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Profile::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $superadminRole = Role::where('role_name','Super Admin')->first();
        $userRole = Role::where('role_name','User')->first();

        $superadmin = User::create(['username' => 'Shahbaz Bin Tahir', 'email' => 'shahbaz.tahir@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Ahsan Wani', 'email' => 'ahsan.wani@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Ashar Qadir', 'email' => 'ashar.qadir@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Muhammad Adnan', 'email' => 'muhammad.adnan@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Sikandar Ali Shah', 'email' => 'sikandar.ali@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Maaz Ali', 'email' => 'maaz.ali@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);

        $superadmin = User::create(['username' => 'Saliha Arif', 'email' => 'saliha.arif@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Tahir Mustafa', 'email' => 'tahir.mustafa@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Umer Farooq', 'email' => 'umer.farooq@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Abdul Waqar', 'email' => 'abdul.waqar@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);
        $superadmin = User::create(['username' => 'Hamza Younas', 'email' => 'hamza.younas@codeinformatics.com','password' => Hash::make('123456789'),]);
        $superadmin->roles()->attach($superadminRole);

        $user = User::create(['username' => '47', 'email' => '47@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
        $user = User::create(['username' => 'Arctix', 'email' => 'arctix@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
        $user = User::create(['username' => 'Ardisam', 'email' => 'Ardisam@tanasales.com','password' => Hash::make('123456789'),]);
        $user->roles()->attach($userRole);
    }
}
