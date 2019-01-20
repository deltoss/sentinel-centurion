<?php

namespace Deltoss\Centurion\Seeders;

use Illuminate\Database\Seeder;

class CenturionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CenturionUsersSeeder::class);
        $this->call(CenturionRolesSeeder::class);
        $this->call(CenturionUserRoleAssignmentsSeeder::class);

        $this->call(CenturionExtensionAbilityCategorySeeder::class);
        $this->call(CenturionExtensionAbilitySeeder::class);
        $this->call(CenturionExtensionRolePermissionAssignmentsSeeder::class);
    }
}
