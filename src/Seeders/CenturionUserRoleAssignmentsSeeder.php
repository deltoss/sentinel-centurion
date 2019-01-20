<?php

namespace Deltoss\Centurion\Seeders;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;

class CenturionUserRoleAssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credentials = [
            'login' => 'developer@example.com',
        ];
        $user = Sentinel::findByCredentials($credentials);
        $role = Sentinel::findRoleBySlug('developer');
        $role->users()->attach($user);

        $credentials = [
            'login' => 'administrator@example.com',
        ];
        $user = Sentinel::findByCredentials($credentials);
        $role = Sentinel::findRoleBySlug('administrator');
        $role->users()->attach($user);

        $credentials = [
            'login' => 'premiumuser@example.com',
        ];
        $user = Sentinel::findByCredentials($credentials);
        $role = Sentinel::findRoleBySlug('premiumuser');
        $role->users()->attach($user);

        $credentials = [
            'login' => 'normaluser@example.com',
        ];
        $user = Sentinel::findByCredentials($credentials);
        $role = Sentinel::findRoleBySlug('normaluser');
        $role->users()->attach($user);
    }
}
