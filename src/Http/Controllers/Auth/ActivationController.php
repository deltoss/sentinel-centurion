<?php

namespace Deltoss\Centurion\Http\Controllers\Auth;

use Sentinel;
use Activation;
use Mail;
use Deltoss\Centurion\Mail\Auth\ActivateAccount;
use Deltoss\Centurion\Mail\Auth\AccountCreated;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Deltoss\Centurion\Http\Requests\Auth\ResendActivationEmailRequest;
use Deltoss\Centurion\Http\Requests\Auth\ActivateRequest;
use Deltoss\Centurion\Http\Requests\Auth\ActivateWithNewPasswordRequest;


class ActivationController extends Controller
{
    /**
     * Shows a form to resend the activation Email
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function showResendActivationEmailForm(Request $request) 
    {
        return view('centurion::auth/activations/email');
    }

    /**
     * Resends the activation email to a user.
     *
     * @param \Deltoss\Centurion\Http\Requests\Auth\ResendActivationEmailRequest $request
     * @return Response
     */
    public function resendActivationEmail(ResendActivationEmailRequest $request) 
    {
        $user = $request->user;
        $email = $request->input('email');

        if ($user)
        {
            Activation::remove($user);
            $activation = Activation::create($user);
            if ($user->created_by_another_user)
                Mail::to($user->email)->send(new AccountCreated($user, $activation));
            else
                Mail::to($user->email)->send(new ActivateAccount($user, $activation));
        }

        return redirect()->route('activate.resend.sent', ['email' => $email]);
    }

    /**
     * Shows confirmation that the mail has been sent
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function activationEmailSent(Request $request, $email)
    {
        return view('centurion::auth/activations/email_sent', compact('email'));
    }

    /**
     * Shows a form to activate a user account.
     * User would need to enter a password.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $email
     * @param string $token
     * @return Response
     */
    public function showActivationWithPasswordForm(Request $request, $email, $token)
    {
        return view("centurion::auth/activations/activate_with_password", compact('email', 'token'));
    }

    /**
     * Activates an account, with a given password
     *
     * @param \Deltoss\Centurion\Http\Requests\Auth\ActivateWithNewPasswordRequest $request
     * @return Response
     */
    public function activateWithPassword(ActivateWithNewPasswordRequest $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');
        
        $user = $request->user;
        $user->change_password_on_activation = false;

        Sentinel::update($user, array('password' => $password));
        return redirect()->route('activate', ['email' => $email, 'token' => $token]);
    }
    
    /**
     * Activates a user account with an activation code and the user email
     *
     * @param \Deltoss\Centurion\Http\Requests\Auth\ActivateRequest $request
     * @param string $email
     * @param string $token
     * @return Response
     */
    public function activate(ActivateRequest $request, $email, $token)
    {
        // Perform validation for the route parameters
        $rules = [
            'token' => 'required',
            'email' => 'required|email',
        ];
        $data = compact('email', 'token');
        $validator = Validator::make($data, $rules);
        $failsValidation = $validator->fails();

        if (!$failsValidation)
        {
            $credentials = [
                'login' => $email,
            ];
            $user = Sentinel::findByCredentials($credentials);
    
            $validator = Validator::make([], []);
            $validator->after(function ($validator) use($email, $user, $token) {
                if(!$user)
                    abort(404);
                else if (Activation::completed($user))
                    $validator->errors()->add('Account Already Activated', trans('centurion::validation.account.already_activated'));
                else if (!Activation::exists($user))
                    $validator->errors()->add('Account Deactivated', trans('centurion::validation.account.deactivated'));
                else if ($user->change_password_on_activation) // If user still needs to change their password on activation, do not activate and pop up an error
                    $validator->errors()->add('Invalid Activation', trans('centurion::validation.account.needs_password_change'));
                else if (!Activation::complete($user, $token)) // Activate user. If could not activate, pop up an error
                    $validator->errors()->add('Invalid Activation', trans('centurion::validation.account.activation_failed'));
            });
            $failsValidation = $validator->fails();
        }

        if ($failsValidation)
        {
            return view('centurion::auth/activations/activation_error')
                ->withErrors($validator) //Flashes validation errors for next request, to show error messages
                ->withInput(
                    $request->except('token')
                );
        }
        else 
        {
            return view('centurion::auth/activations/activation_success');
        }
    }
}
