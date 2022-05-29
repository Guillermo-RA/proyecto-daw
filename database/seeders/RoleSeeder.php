<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Roles

        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        //Permissions

        $editProfilePermission = Permission::create(['name' => 'edit_profile']);
        $makeOrdersPermission = Permission::create(['name' => 'make_orders']);

        $createUsersPermission = Permission::create(['name' => 'create_users']);

        $createAdminsPermission = Permission::create((['name' => 'createa_admins']));

        $superAdminRole->givePermissionTo([$createAdminsPermission]);
        $createUsersPermission->assignRole([$superAdminRole, $adminRole]);
        $editProfilePermission->assignRole([$superAdminRole, $adminRole, $clientRole]);
        $clientRole->givePermissionTo([$makeOrdersPermission]);
    }
}