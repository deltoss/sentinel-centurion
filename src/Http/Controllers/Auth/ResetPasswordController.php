<?php

namespace Deltoss\Centurion\Http\Controllers\Auth;

use Sentinel;
use Reminder;
use Mail;

use Deltoss\Centurion\Http\Requests\Auth\ResetRequest;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * This controller is responsible for handling password reset requests.
 */
class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view for the given token.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $userId, $token)
    {
        return view('centurion::auth.passwords.reset')->with([
            'userId' => $userId, 
            'token' => $token
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param Deltoss\Centurion\Http\Requests\Auth\ResetRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetRequest $request)
    {
        $token = $request->input('token');
        $newPassword = $request->input('password');
        $userId = $request->input('user_id');
        $user = Sentinel::findUserById($userId);

        $errors = [];
        if (!$user || !Reminder::complete($user, $token, $newPassword))
            $errors['Invalid Password Reset'] = trans('centurion::validation.account.password_reset_failed');
        
        if (count($errors) < 1)
        {
            // Flash stores variables only for the next request, and will be deleted from session afterwards
            // Useful to show messages
            $request->session()->flash('message', trans('centurion::forgot_password.labels.password_reset_success'));
            return redirect()->route('login.request');
        }
        else 
        {
            return redirect()->back()->withErrors($errors);
        }
    }
}
