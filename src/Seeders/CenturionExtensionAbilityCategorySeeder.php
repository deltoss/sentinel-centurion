<?php

namespace Deltoss\Centurion\Seeders;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;

class CenturionExtensionAbilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Sentinel::getAbilityCategoryRepository()->createModel();
        $category->name = "Users";
        $category->save();

        $category = Sentinel::getAbilityCategoryRepository()->createModel();
        $category->name = "Roles";
        $category->save();

        $category = Sentinel::getAbilityCategoryRepository()->createModel();
        $category->name = "Permissions";
        $category->save();
    }
}
