<?php

namespace Deltoss\Centurion\Seeders;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use \Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Database\Seeder;

class CenturionRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a new role
        
        Sentinel::getRoleRepository()->createModel()->create([
            'name' => "Developer",
            'slug' => "developer",
        ]);

        Sentinel::getRoleRepository()->createModel()->create([
            'name' => "Administrator",
            'slug' => "administrator",
        ]);

        Sentinel::getRoleRepository()->createModel()->create([
            'name' => "Premium User",
            'slug' => "premiumuser",
        ]);

        Sentinel::getRoleRepository()->createModel()->create([
            'name' => "Normal User",
            'slug' => "normaluser",
        ]);
    }
}
