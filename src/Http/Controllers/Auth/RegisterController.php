<?php

namespace Deltoss\Centurion\Http\Controllers\Auth;

use Sentinel;
use Activation;
use Mail;
use Deltoss\Centurion\Mail\Auth\ActivateAccount;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Deltoss\Centurion\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * View the register page
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function showRegistrationForm(Request $request)
    {
        return view('centurion::auth/register');
    }

    /**
     * Signs up the user
     *
     * @param Deltoss\Centurion\Http\Requests\RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        // Create a new user
        $user = Sentinel::register([
			// Make sure all array keys are database
			// fields that corresponds to your User
			// model's $fillable property.
			// See Eloquent Mass Assignments
			// for more details.
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ]);

        // To login, user must be activated, so send an activation email for the user
        $activation = Activation::create($user);
        Mail::to($user->email)->send(new ActivateAccount($user, $activation));
        return redirect()->route('register.completed', ['email' => $user->email]);
    }

    /**
     * Completes the registration, and ask the user to activate their account.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function registrationCompleted(Request $request, $email)
    {
        return view('centurion::auth/registration_completed', compact('email'));
    }
}
