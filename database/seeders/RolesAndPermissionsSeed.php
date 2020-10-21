<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('permissions.default_roles');
        foreach($roles as $role)
        {
            Role::firstOrCreate([
                'title' => $role['title'],
                'fa_title' => $role['fa_title']
            ]);
        }

        $permissions = config('permissions.default_permissions');
        foreach($permissions as $permission)
        {
            Permission::firstOrCreate([
                'title' => $permission['title'],
                'fa_title' => $permission['fa_title']
            ]);
        }

        $role = Role::where('title','Super_Admin')->first();
        $role->permissions()->sync(Permission::select('id')->get());
    }
}
