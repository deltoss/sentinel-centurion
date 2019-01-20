<?php

namespace Deltoss\Centurion\Http\Controllers\Profile;

use Sentinel;
use Activation;

use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Profile\UpdateProfileRequest;
use Deltoss\Centurion\Http\Requests\Profile\ChangeProfilePasswordRequest;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    /**
     * Show the logged-in user's account information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Sentinel::getUser();
        if(!$user)
            abort(404);

        $userRoles = $user->roles;
        $userAbilities = $user->abilities;
        return view("centurion::profile/index", compact('user', 'userRoles', 'userAbilities'));
    }

    /**
     * Show the form for editing the current logged-in user's account.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = Sentinel::getUser();
        if(!$user)
            abort(404);

        return view("centurion::profile/edit", compact('user'));
    }

    /**
     * Updates the current logged-in user's account.
     *
     * @param \Deltoss\Centurion\Http\Requests\Profile\UpdateProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user;

        $originalEmail = $user->email;

        // Make sure all array keys from the request
        // (i.e. your GET or POST parameters)
        // matches the database fields that 
        // corresponds to your User
        // model's $fillable property.
        // See Eloquent Mass Assignments
        // for more details.
        $credentials = $request->all();
        
        $user = Sentinel::update($user, $credentials);

        $message = trans('centurion::profile.labels.update_success');
        if ($originalEmail != $credentials['email'])
            $message = $message . ' ' . trans('centurion::profile.labels.email_change_note', ['email' => $credentials['email']]);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', $message);
        // redirect
        return redirect()->route('profile.index');
    }

    /**
     * Shows the change password form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm(Request $request)
    {
        $user = Sentinel::getUser();
        return view("centurion::profile/change_password", compact('user'));
    }

    /**
     * Changes the password for current logged in user.
     *
     * @param \Deltoss\Centurion\Http\Requests\Profile\ChangeProfilePasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangeProfilePasswordRequest $request)
    {
        $user = $request->user;

        $password = $request->input('new_password');
        Sentinel::update($user, array('password' => $password));

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::profile.labels.change_password_success'));
        // redirect
        return redirect()->route('profile.index');
    }
}
