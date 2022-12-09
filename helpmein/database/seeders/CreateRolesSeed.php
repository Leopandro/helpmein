<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Role $role */
        $role = Role::findOrCreate('Teacher');
        $permission1 = Permission::findOrCreate('create client');
        $permission2 = Permission::findOrCreate('view client');
        $permission3 = Permission::findOrCreate('edit client');
        $permission4 = Permission::findOrCreate('delete client');
        $role->syncPermissions([$permission1, $permission2, $permission3, $permission4]);

        /** @var Role $role */
        $role = Role::findOrCreate('Client');
        $permission1 = Permission::findOrCreate('view task');
        $role->syncPermissions([$permission1]);
    }
}
