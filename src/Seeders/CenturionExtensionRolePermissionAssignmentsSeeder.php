<?php

namespace Deltoss\Centurion\Seeders;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;

class CenturionExtensionRolePermissionAssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Sentinel::findRoleBySlug('developer');
        static::attachPermissionToRole($role, 'createusers');
        static::attachPermissionToRole($role, 'viewusers');
        static::attachPermissionToRole($role, 'editusers');
        static::attachPermissionToRole($role, 'deleteusers');
        static::attachPermissionToRole($role, 'createroles');
        static::attachPermissionToRole($role, 'viewroles');
        static::attachPermissionToRole($role, 'editroles');
        static::attachPermissionToRole($role, 'deleteroles');
        static::attachPermissionToRole($role, 'createpermissions');
        static::attachPermissionToRole($role, 'viewpermissions');
        static::attachPermissionToRole($role, 'editpermissions');
        static::attachPermissionToRole($role, 'deletepermissions');

        // Alternatively, using Sentinel property assignments
        //   $role->permissions = ['createusers' => true, 'viewusers' => true, 'editusers' => true, 'deleteusers' => true];

        $role = Sentinel::findRoleBySlug('administrator');
        static::attachPermissionToRole($role, 'createusers');
        static::attachPermissionToRole($role, 'viewusers');
        static::attachPermissionToRole($role, 'editusers');
        static::attachPermissionToRole($role, 'deleteusers');
        static::attachPermissionToRole($role, 'createroles');
        static::attachPermissionToRole($role, 'viewroles');
        static::attachPermissionToRole($role, 'editroles');
        static::attachPermissionToRole($role, 'deleteroles');
    }

    private static function attachPermissionToRole($role, $permissionSlug) {
        $ability = Sentinel::getAbilityRepository()->where('slug', $permissionSlug)->first(); // model or null
        if (!$ability) {
           throw new Exception('Could not find the permission with slug of \'' . $permissionSlug . '\'');
        }

        $role->addPermission($ability->id, true);
        // Alternatively, using Eloquent instead of Sentinel methods:
        //   $ability->roles()->attach($role, ['allowed' => true]);
    }
}