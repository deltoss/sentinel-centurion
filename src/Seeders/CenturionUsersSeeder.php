<?php

namespace Deltoss\Centurion\Seeders;

use Illuminate\Database\Seeder;

// Import the necessary sentinel classes
use \Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use \Cartalyst\Sentinel\Laravel\Facades\Activation;

class CenturionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Register a new user
        // To login, user must be activated
        $user = Sentinel::registerAndActivate([
			// Make sure all array keys are database
			// fields that corresponds to your User
			// model's $fillable property.
			// See Eloquent Mass Assignments
			// for more details.
            'email'    => 'developer@example.com',
            'password' => 'developer',
            'first_name' => "Devin",
            'last_name' => "Nevilla"
        ]);

        $user = Sentinel::registerAndActivate([
            'email'    => 'administrator@example.com',
            'password' => 'administrator',
            'first_name' => "John",
            'last_name' => "Smith"
        ]);

        $user = Sentinel::registerAndActivate([
            'email'    => 'premiumuser@example.com',
            'password' => 'premiumuser',
            'first_name' => "Mary",
            'last_name' => "Jones"
        ]);

        $user = Sentinel::registerAndActivate([
            'email'    => 'normaluser@example.com',
            'password' => 'normaluser',
            'first_name' => "Bob",
            'last_name' => "Bartright"
        ]);

        // We do not create any activation records
        // for this user, thus making user deactivated
        $user = Sentinel::register([
            'email'    => 'deactivateduser@example.com',
            'password' => 'deactivateduser',
            'first_name' => "Tracy",
            'last_name' => "Ownswealth"
        ]);

        // We create an activation record for the user
        // This makes the user unactivated. The user 
        // needs to activate his/her account
        $user = Sentinel::register([
            'email'    => 'unactivateduser@example.com',
            'password' => 'unactivateduser',
            'first_name' => "Ruby",
            'last_name' => "Bronzehire"
        ]);
        $activation = Activation::create($user);
    }
}
