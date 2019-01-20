<?php

namespace Deltoss\Centurion\Seeders;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;

class CenturionExtensionAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionCategoryId = Sentinel::getAbilityCategoryRepository()->findByName('Users')->id;
        
        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Create Users";
        $permission->slug = "createusers";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "View Users";
        $permission->slug = "viewusers";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Edit Users";
        $permission->slug = "editusers";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Delete Users";
        $permission->slug = "deleteusers";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permissionCategoryId = Sentinel::getAbilityCategoryRepository()->findByName('Roles')->id;

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Create Roles";
        $permission->slug = "createroles";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "View Roles";
        $permission->slug = "viewroles";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Edit Roles";
        $permission->slug = "editroles";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Delete Roles";
        $permission->slug = "deleteroles";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permissionCategoryId = Sentinel::getAbilityCategoryRepository()->findByName('Permissions')->id;

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Create Permissions";
        $permission->slug = "createpermissions";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "View Permissions";
        $permission->slug = "viewpermissions";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Edit Permissions";
        $permission->slug = "editpermissions";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();

        $permission = Sentinel::getAbilityRepository()->createModel();
        $permission->name = "Delete Permissions";
        $permission->slug = "deletepermissions";
        $permission->ability_category_id = $permissionCategoryId;
        $permission->save();
    }
}
