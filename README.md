# Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Requirements](#requirements)
- [Quick Start with New Laravel App](#quick-start)
- [Centurion Middleware](#centurion-middleware)
- [Seeders](#seeders)
- [Extending Centurion](#extending)
  * [Configurations](#extending-configurations)
  * [Views](#extending-views)
  * [Mails](#extending-mails)
  * [Models](#extending-models)
  * [Routes](#extending-routes)
  * [Controllers](#extending-controllers)
  * [Validations](#extending-validations)
  * [Migrations](#extending-migrations)
  * [Seeders](#extending-seeders)
  * [Translations](#extending-translations)
- [CAPTCHA](#captcha)
  * [reCAPTCHA](#recaptcha)
  * [No-CAPTCHA Package](#no-captcha-package)
  * [Development reCAPTCHA API Key for Testing](#development-recaptcha-api-key-for-testing)
  * [Create your reCAPTCHA API Key](#create-your-recaptcha-api-key)
  * [Enabling reCAPTCHA](#enabling-recaptcha)

# <a name="Introduction"></a>Introduction
A [Laravel](https://github.com/laravel/laravel) package that provides opinionated authentication scaffolding using [Cartalyst Sentinel](https://cartalyst.com/manual/sentinel/2.0). It provides various features out of the box, and is designed so that you can extend pretty much anything such as the layout, routes, controllers actions, views, and so on.

# Features
* Login
* Registration with Activation Workflow
* Forgot Password Workflow
* User Management
* Role Management
* Ability/Permission Management
* Activate/Deactivate Users
* Google reCAPTCHA
* Administrators can Register Users
* Authentication and Authorisation Middleware
* Localisation

# <a name="requirements"></a>Requirements
* Sentinal Database Permissions
* Laravel Framework 5.5+
* Cartalyst Sentinel 2.0+
* php 7.1.3+

# <a name="quick-start"></a>Quick Start with New Laravel App

**Install the package using composer**

```shell
$ composer require deltoss/sentinel-centurion
```

**Delete the default Laravel auth files using the below command**
```shell
$ php artisan centurion:spruce
```

**If you haven't done so already, publish the Sentinel assets**
```shell
$ php artisan vendor:publish --provider='Cartalyst\Sentinel\Laravel\SentinelServiceProvider'
```

**Run your database migrations**
```shell
$ php artisan migrate
```

**Publish the Centurion assets (js and css files)**
```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=public
```

**Call the below command to seed the initial Centurion data to the database.**
```shell
php artisan db:seed --class=Deltoss\Centurion\Seeders\CenturionDatabaseSeeder
```
Alternatively you can call the Centurion seeder from your own seeder files. For more information, see the [seeder](#seeder) section.

**For Centurion to send password recovery and activation emails, add your mail server to your `.env` file**
It could look something like this:
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=YOURUSERNAME
MAIL_PASSWORD=YOURPASSWORD
MAIL_ENCRYPTION=null
```
For more information, refer to the official Laravel docs for [mails](https://laravel.com/docs/5.7/mail)

**Navigate to any of the Centurion URLs, and you can login using the below default credentials:**
```
Role: Developer
UN: developer@example.com
PW: developer

Role: Administrator
UN: administrator@example.com
PW: administrator
```

Here are the main Centurion URLs:
| URL | Description |
| --- | ----------- |
| `/profile` | The current logged-in user profile. |
| `/users` | User management. |
| `/roles` | Role management. |
| `/abilities` | Ability management. |
| `/register` | User registration. |
| `/login` | Login. You can also troubleshoot login from here. |
| `/logout` | Clears the sessions and logs out. |


# <a name="centurion-middleware"></a>Centurion Middleware

Centurion has several middlewares so you can secure your routes. Their purpose and example usage are listed below:
| Middleware Class  | Usage Example  |
|---|---|
| Deltoss\Centurion\Http\Middleware\CenturionRedirectIfAuthenticated    | ```"centurion.guest"``` |
| Deltoss\Centurion\Http\Middleware\CenturionAuthenticate               | ```"centurion.auth"``` |
| Deltoss\Centurion\Http\Middleware\CenturionCheckAllPermissions        | ```"centurion.hasaccess:abilityslug1,abilityslugN"``` |
| Deltoss\Centurion\Http\Middleware\CenturionCheckAnyPermissions        | ```"centurion.hasanyaccess:abilityslug1,abilityslugN"``` |
| Deltoss\Centurion\Http\Middleware\CenturionCheckRole                  | ```"centurion.hasrole:roleslug1,roleslugN"``` |

Example:
```php
Route::middleware(['centurion.auth', 'centurion.hasaccess:editroles'])->group(function () {
    // Resource controller route
    Route::resource('/roles', 'Deltoss\Centurion\Http\Controllers\Roles\RoleController')->only([
        'edit', 'update'
    ]);
});
```

# <a name="seeding"></a>Seeding

There are two approaches you can set up Centurion seeders. First is simply calling the below PHP artisan command:
```shell
php artisan db:seed --class=Deltoss\Centurion\Seeders\CenturionDatabaseSeeder
```

However, this is only convenient if the only seeder you have is the Centurion seeders. If you have your own set of data to seed, it'd mean you'll need to run a command to call Centurion seeders, and additionally another command to call your own seeders each time you refresh or re-seed your data.

The second approach solves that issue. You can call the Centurion seeder from your own seeder files, so you can just use a single Laravel seed command that calls the Centurion seeder, and your own set of seeder files as well.

For instance, you can create/modify your DatabaseSeeder class in `database/seeds/DatabaseSeeder.php`, and have it call the main Centurion Seeder:
```php
use Illuminate\Database\Seeder;
use Deltoss\Centurion\Seeders\CenturionDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CenturionDatabaseSeeder::class);

        // Put your seeder code here...
    }
}
```

If you created a new database seeder file, you'll need to run the `composer dump-autoload` command to refresh your autoload files before you call your Laravel migration commands.

After setting up the seeder, you can use `php artisan db:seed`, `php artisan migrate --seed` or `php artisan migrate:refresh --seed` command to seed the data.

# <a name="extending"></a>Extending Centurion

## <a name="extending-configurations"></a>Configurations
There are various configurations with Centurion. You can publish the config file and tweak the default Centurion behaviour using the below command:

```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=config
```

## <a name="extending-views"></a>Views
You can copy over the views with the below command, and then override it as necessary. This will publish the Centurion views into your `resources/views/vendor/centurion` folder.

```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=views
```

With Centurion views, it's possible to change the layout with minimal effort.
You can also change the centurion layouts through changing the following views:
| File | Description |
| ---- | ----------- |
| `layouts/layout.blade.php` | The main layout file. |
| `layouts/auth_layout.blade.php` | Layout for forgot-password, activation email, etc. |
| `layouts/main_layout.blade.php` | Layout for the other components once logged in (e.g. user management, profile). If you want to modify/omit the default sidebar menu, you should modify this file. |

`auth_layout.blade.php` and `layouts/main_layout.blade.php` can be adjusted to point to a different layout file (e.g. your application main layout) to change the look and feel of Centurion.

## <a name="extending-mails"></a>Mails
After you publish the Centurion [views](#extending-views), you'll also see that there would be a `resources/views/vendor/mails` folder, defining the look and feel for the various emails that Centurion uses.

## <a name="extending-models"></a>Models

Refer to the `Sentinel Database Permissions` package if you want to extend the models.

## <a name="extending-routes"></a>Routes
You have several options with overriding/adding Centurion routes. 
  * You can publish the Centurion routes, then add/override/remove routes there.
  * The other method is to add routes to your in `routes/web.php` file

**Adding to your Routes File**

You can add routes to your in `routes/web.php` file. This way, you can also override routes from Centurion. However note you can't **remove** existing centurion routes without publishing the Centurion routes.

**Publishing Centurion Routes**

You can publish the Centurion routes to `routes/vendor/centurion` folder using the below command:

```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=routes
```

From the route files within `routes/vendor/centurion`, you can add additional routes, override existing ones, or even remove routes entirely.

## <a name="extending-controllers"></a>Controllers
You may want to override Centurion controllers for adding/modifying additional business logic.

Create a controller with the below artisan command:
```shell
php artisan make:controller UserController
```

Open up your controller file, and have it inherit from a Centurion controller. To see what Controllers you can override, take a look at the online repository, or you can look at your `vendor` folder. The below is what it should look like:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Controllers\Users\UserController as CenturionUserController;

class UserController extends CenturionUserController
{
    public function index(Request $request)
    {
        return view('centurion::users/index');
    }
}
```

With the above code, we override the index method, but now we also need to update our routes so the previous Centurion route would be overridden so it'd point to the new index method. For more information, refer to [routes](#extending-routes).

## <a name="extending-validations"></a>Validations
Centurion users Illuminates **FormRequest** classes. First, you'll need to create a FormRequest class that inherits from an existing Centurion FormRequest class. To know which Centurion FormRequest to inherit from, you'll need to peek around the Centurion source code (either looking at your `vendor` folder or online repository), namely:
  1. Look into the Centurion Controller which you want to adjust validation logic for.
  2. Find the affected action(s) you want to override validation logic for.
  3. On the method declaration, it should state the specific FormRequest class used. E.g:
     ```php
     namespace Deltoss\Centurion\Http\Controllers\Users;

     use Illuminate\Http\Request;
     use Illuminate\Routing\Controller;
     use Deltoss\Centurion\Http\Requests\Users\UpdateUserRequest;

     class UserController extends CenturionUserController
     {
         // ...

         /**
          * Updates an existing resource in storage.
          *
          * @param \Deltoss\Centurion\Requests\UpdateUserRequest $request
          * @param int $id
          * @return \Illuminate\Http\Response
          */
         public function update(UpdateUserRequest $request, $id)
         {
             // ...
         }
        
         // ...
     }
     ```
     From the above code, we know the `Deltoss\Centurion\Http\Requests\Users\UpdateUserRequest` was used for the update method, called when updating details for a user.

Now that we know the target Centurion FormRequest class to override, lets make a new FormRequest class using the below command:
```shell
$ php artisan make:request AppUpdateUserRequest
```

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppUpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
```

Modify the above code to have additional validation logic:
  1. Either set the `authorises` to return true, or remove it altogether. Centurion already handles that.
  2. Inherit from Centurion `UpdateUserRequest` class.
  3. Add additional validation logic to the `rules()` method. Make sure to call the parent function so the original validation rules are still in place.

It can look like the following:
```php
namespace App\Http\Requests;

use Deltoss\Centurion\Http\Requests\Users\UpdateUserRequest;

class AppUpdateUserRequest extends UpdateUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $inheritedRules = parent::rules();
        $newRules = [
            'title' => 'required',
            'staff_number' => 'nullable|numeric',
        ];
        return array_merge($inheritedRules, $newRules);
    }
}
```

We need to override the Centurion controller. If you haven't already created the controller, see [Extending Centurion Controllers](#extending-controllers). After you have a Controller that overrides the default Centurion with, you can make use of Laravel **IoC (Inversion of Control)** to swap out the Centurion default FormRequest class, with your modified one with the controller constructor method.
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Controllers\Users\UserController as CenturionUserController;
use App\Http\Requests\AppUpdateUserRequest;
use Deltoss\Centurion\Http\Requests\Users\UpdateUserRequest as CenturionUpdateUserRequest;

class UserController extends CenturionUserController
{
    public function __construct()
    {
        // Override default service container
        // bindings to override the Centurion
        // Form request used to provide 
        // additional validation logic
        app()->bind(CenturionUpdateUserRequest::class, function($app) {
            return $app->make(AppUpdateUserRequest::class);
        });
    }
    // ...
}

```

Note you'll now need to override your Centurion routes so that it'll point to the affected actions on your new controller. For more information, see [Extending Routes](#extending-routes). You may end up with putting something similar to the following into your routes:
```php
    // Resource controller route
    Route::resource('/users', 'App\Http\Controllers\UserController')->only([
        'update'
    ]);
```

## <a name="extending-migrations"></a>Migrations
Centurion has migration files to modify existing Sentinel tables for additional features, including:
  * Changing password upon activation for accounts created by Administrators
  * Switching the content of the activation email sent to users based on whether their account was created by Administrators or not.

It's optional to publish those migrations, as the Centurion migrations would be automatically executed when you run the `php artisan migrate` or related commands. It's generally a better approach to instead add new migrations if you'd like to adjust/remove features. To publish migrations, use the below command:

```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=migrations
```

If you're looking to modify migration files for database permissions, then you should look into the `Sentinel Database Permissions` package and their documentation.

## <a name="extending-seeders"></a>Seeders
Centurion seeders are provided to make seeding dummy data which makes it quicker to set up your authentication data.

You can call the Centurion seeder classes from your own database seeders. Below is how you may set up your Laravel `DatabaseSeeder` class to call Centurion seeders.

```php
use Illuminate\Database\Seeder;
use Deltoss\Centurion\Seeders\CenturionDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CenturionDatabaseSeeder::class);
    }
}
```

If you don't want to run all the Centurion seeders, and only a subset of it, you can do so by selectively choosing the specific seeder classes to call.

```php
use Illuminate\Database\Seeder;
use Deltoss\Centurion\Seeders\CenturionUsersSeeder;
use Deltoss\Centurion\Seeders\CenturionRolesSeeder;
use Deltoss\Centurion\Seeders\CenturionUserRoleAssignmentsSeeder;
use Deltoss\Centurion\Seeders\CenturionExtensionAbilityCategorySeeder;
use Deltoss\Centurion\Seeders\CenturionExtensionAbilitySeeder;
use Deltoss\Centurion\Seeders\CenturionExtensionRolePermissionAssignmentsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
```

After setting up the seeder, you can use `php artisan db:seed`, `php artisan migrate --seed` or `php artisan migrate:refresh --seed` command to seed the data.

## <a name="extending-translations"></a>Translations
Centurion utilises Laravel default translation system. You can easily publish those translations, and add/modify translations as per Laravel translation documentation. To publish the Centurion translations, use the below command:

```shell
$ php artisan vendor:publish --provider='Deltoss\Centurion\Providers\CenturionServiceProvider' --tag=translations
```

At this stage, only English is available out of the box.

# <a name="captcha"></a>CAPTCHA
This project utilises a package called `no-captcha`, which implements Google reCAPTCHA For Laravel.

CAPTCHA is the human validation test to prevent bots from spamming websites. This is done through providing images of letters unreadable to bots, where users enter the letters.

## <a name="recaptcha"></a>reCAPTCHA
reCAPTCHA offers more than just spam protection. Every time CAPTCHAs are solved, that human effort helps digitize text, annotate images, and build machine learning datasets. This in turn helps preserve books, improve maps, and solve hard AI problems.

## <a name="no-captcha-package"></a>No-CAPTCHA Package
For this project, we use the "no-captcha" package which integrates Google reCAPTCHA with Laravel.
Refer to the [githib repository page](https://github.com/anhskohbo/no-captcha) for more information

Note that you must either register for an API key, or alternatively use the development key provided by Google.

## <a name="development-recaptcha-api-key-for-testing"></a>Development reCAPTCHA API Key for Testing
With the following test keys, you will always get No CAPTCHA and all verification requests will pass.

```
Site key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
Secret key: 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```
The reCAPTCHA widget will show a warning message to ensure it's not used for production traffic.

## <a name="create-your-recaptcha-api-key"></a>Create your reCAPTCHA API Key
This requires you to have a Google account. For business purposes, it's recommended for you to create a business Google account, or you can register a google account with your business email.

Simply go to this [site](https://www.google.com/recaptcha/admin), and follow through the wizard, and you should get your reCAPTCHA API key.

Note that you'd need to register your site to the CAPTCHA API key. For development purposes, you can simply enter in "localhost" as a domain for testing purposes. Alternatively, you may disable domain name validation altogether.

For more information, see this link [here](https://developers.google.com/recaptcha/docs/domain_validation)

## <a name="enabling-recaptcha"></a>Enabling reCAPTCHA
For Centurion to use Google reCAPTCHA. Add your Google reCAPTCHA keys to your ".env" file.
```
NOCAPTCHA_SITEKEY=YOURSITEKEY
NOCAPTCHA_SECRET=YOURSECRET
```