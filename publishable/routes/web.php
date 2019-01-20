<?php

/*
|--------------------------------------------------------------------------
| Centurion Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['middleware' => ['web']], function () {
    // Auth routes
    Route::post('logout', 'Deltoss\Centurion\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('unauthorised', 'Deltoss\Centurion\Http\Controllers\Auth\LoginController@unauthorised')->name('unauthorised');
    
    Route::middleware(['centurion.guest'])->group(function () {
        // Authentication Routes...
        Route::get('login', 'Deltoss\Centurion\Http\Controllers\Auth\LoginController@showLoginForm')->name('login.request');
        Route::post('login', 'Deltoss\Centurion\Http\Controllers\Auth\LoginController@login')->name('login');
        Route::get('login/troubleshoot', 'Deltoss\Centurion\Http\Controllers\Auth\LoginController@showLoginTroubleshootForm')->name('login.troubleshoot');
    
        if (config('centurion.registration.enabled'))
        {
            // Registration Routes...
            Route::get('register', 'Deltoss\Centurion\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register.request');
            Route::post('register', 'Deltoss\Centurion\Http\Controllers\Auth\RegisterController@register')->name('register');
            Route::get('register/completed/{email}', 'Deltoss\Centurion\Http\Controllers\Auth\RegisterController@registrationCompleted')->name('register.completed');
        }
    
        // Forgot Password Routes...
        Route::get('password/reset', 'Deltoss\Centurion\Http\Controllers\Auth\ForgotPasswordController@showSendResetLinkForm')->name('forgot_password.request');
        Route::post('password/email', 'Deltoss\Centurion\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot_password.email');
        Route::get('password/email/sent/{email}', 'Deltoss\Centurion\Http\Controllers\Auth\ForgotPasswordController@passwordResetEmailSent')->name('forgot_password.email_sent');
        
        // Password Reset Routes
        Route::get('password/reset/{userId}/{token}', 'Deltoss\Centurion\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('reset_password.request');
        Route::post('password/reset', 'Deltoss\Centurion\Http\Controllers\Auth\ResetPasswordController@reset')->name('reset_password');
    
        // Resend activation Routes...
        Route::get('activate/resend', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@showResendActivationEmailForm')->name('activate.resend.request');
        Route::post('activate/email', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@resendActivationEmail')->name('activate.resend');
        Route::get('activate/email/sent/{email}', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@activationEmailSent')->name('activate.resend.sent');
        
        // Activation Routes...
        Route::get('activate/{email}/{token}', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@activate')->name('activate');
        Route::get('activate-with-password/{email}/{token}', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@showActivationWithPasswordForm')->name('activate_with_password.request');
        Route::post('activate-with-password', 'Deltoss\Centurion\Http\Controllers\Auth\ActivationController@activateWithPassword')->name('activate_with_password');
    });
    
    Route::middleware(['centurion.auth'])->group(function () {
        Route::get('/profile', 'Deltoss\Centurion\Http\Controllers\Profile\ProfileController@index')->name('profile.index');
        Route::get('/profile/edit', 'Deltoss\Centurion\Http\Controllers\Profile\ProfileController@edit')->name('profile.edit');
        Route::put('/profile', 'Deltoss\Centurion\Http\Controllers\Profile\ProfileController@update')->name('profile.update');
    
        Route::get('/profile/change-password', 'Deltoss\Centurion\Http\Controllers\Profile\ProfileController@showChangePasswordForm')->name('profile.change_password.request');
        Route::put('/profile/change-password', 'Deltoss\Centurion\Http\Controllers\Profile\ProfileController@changePassword')->name('profile.change_password');
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:createusers'])->group(function () {
        // Resource controller route
        Route::resource('/users', 'Deltoss\Centurion\Http\Controllers\Users\UserController')->only([
            'create', 'store'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:viewusers'])->group(function () {
        // Resource controller route
        Route::resource('/users', 'Deltoss\Centurion\Http\Controllers\Users\UserController')->only([
            'index', 'show'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:editusers'])->group(function () {
        // User activation/deactivation routes
        Route::post('/users/{id}/activate', 'Deltoss\Centurion\Http\Controllers\Users\UserController@activate')->name('users.activate');
        Route::delete('/users/{id}/deactivate', 'Deltoss\Centurion\Http\Controllers\Users\UserController@deactivate')->name('users.deactivate');

        // Sending email routes
        Route::post('/users/{id}/email/activate', 'Deltoss\Centurion\Http\Controllers\Users\UserController@sendActivationEmail')->name('users.email.activate');
        Route::post('/users/{id}/email/reset-password', 'Deltoss\Centurion\Http\Controllers\Users\UserController@sendResetPasswordEmail')->name('users.email.reset_password');
        
        // Routes for accessing the role assignment pages, for a given user
        Route::get('/users/{id}/assign-roles', 'Deltoss\Centurion\Http\Controllers\Users\Roles\UserRoleController@assignRoles')->name('users.roles.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/users/{id}/sync-roles', 'Deltoss\Centurion\Http\Controllers\Users\Roles\UserRoleController@syncRoles')->name('users.roles.sync');
    
        // Routes for accessing the permission assignment pages, for a given user
        Route::get('/users/{id}/assign-permissions', 'Deltoss\Centurion\Http\Controllers\Users\Abilities\UserAbilityController@assignPermissions')->name('users.abilities.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/users/{id}/sync-permissions', 'Deltoss\Centurion\Http\Controllers\Users\Abilities\UserAbilityController@syncPermissions')->name('users.abilities.sync');
    
        // Resource controller route
        Route::resource('/users', 'Deltoss\Centurion\Http\Controllers\Users\UserController')->only([
            'edit', 'update'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:deleteusers'])->group(function () {
        // Resource controller route
        Route::resource('/users', 'Deltoss\Centurion\Http\Controllers\Users\UserController')->only([
            'destroy'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:createroles'])->group(function () {
        // Resource controller route
        Route::resource('/roles', 'Deltoss\Centurion\Http\Controllers\Roles\RoleController')->only([
            'create', 'store'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:viewroles'])->group(function () {
        // Resource controller route
        Route::resource('/roles', 'Deltoss\Centurion\Http\Controllers\Roles\RoleController')->only([
            'index', 'show'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:editroles'])->group(function () {
        // Routes for accessing the user assignment pages, for a given role
        Route::get('/roles/{id}/assign-users', 'Deltoss\Centurion\Http\Controllers\Roles\Users\RoleUserController@assignUsers')->name('roles.users.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/roles/{id}/sync-users', 'Deltoss\Centurion\Http\Controllers\Roles\Users\RoleUserController@syncUsers')->name('roles.users.sync');
    
        // Routes for accessing the permission assignment pages, for a given role
        Route::get('/roles/{id}/assign-permissions', 'Deltoss\Centurion\Http\Controllers\Roles\Abilities\RoleAbilityController@assignPermissions')->name('roles.abilities.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/roles/{id}/sync-permissions', 'Deltoss\Centurion\Http\Controllers\Roles\Abilities\RoleAbilityController@syncPermissions')->name('roles.abilities.sync');
        
        // Resource controller route
        Route::resource('/roles', 'Deltoss\Centurion\Http\Controllers\Roles\RoleController')->only([
            'edit', 'update'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:deleteroles'])->group(function () {
        // Resource controller route
        Route::resource('/roles', 'Deltoss\Centurion\Http\Controllers\Roles\RoleController')->only([
            'destroy'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:createpermissions'])->group(function () {
        // Resource controller route
        Route::resource('/abilities', 'Deltoss\Centurion\Http\Controllers\Abilities\AbilityController')->only([
            'create', 'store'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:viewpermissions'])->group(function () {
        // Resource controller route
        Route::resource('/abilities', 'Deltoss\Centurion\Http\Controllers\Abilities\AbilityController')->only([
            'index', 'show'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:editpermissions'])->group(function () {
        // Routes for accessing the user assignment pages, for a given ability
        Route::get('/abilities/{id}/assign-users', 'Deltoss\Centurion\Http\Controllers\Abilities\Users\AbilityUserController@assignUsers')->name('abilities.users.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/abilities/{id}/sync-users', 'Deltoss\Centurion\Http\Controllers\Abilities\Users\AbilityUserController@syncUsers')->name('abilities.users.sync');
    
        // Routes for accessing the role assignment pages, for a given ability
        Route::get('/abilities/{id}/assign-roles', 'Deltoss\Centurion\Http\Controllers\Abilities\Roles\AbilityRoleController@assignRoles')->name('abilities.roles.assign');
        // Assignments routes, that processes and performs the assignments
        Route::put('/abilities/{id}/sync-roles', 'Deltoss\Centurion\Http\Controllers\Abilities\Roles\AbilityRoleController@syncRoles')->name('abilities.roles.sync');
        
        // Resource controller route
        Route::resource('/abilities', 'Deltoss\Centurion\Http\Controllers\Abilities\AbilityController')->only([
            'edit', 'update'
        ]);
    });
    
    Route::middleware(['centurion.auth', 'centurion.hasaccess:deletepermissions'])->group(function () {
        // Resource controller route
        Route::resource('/abilities', 'Deltoss\Centurion\Http\Controllers\Abilities\AbilityController')->only([
            'destroy'
        ]);
    });
});