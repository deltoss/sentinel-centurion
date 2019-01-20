<?php

namespace Deltoss\Centurion\Http\Controllers\Auth;

use Sentinel;
use Reminder;
use Mail;
use Deltoss\Centurion\Mail\Auth\ForgotPassword;
use Deltoss\Centurion\Http\Requests\Auth\SendResetLinkEmailRequest;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

/**
 * This controller is responsible for handling password reset emails.
 * It sends a forgot password email to users, containing links
 * to reset their passwords. This controller does not actually
 * reset the password. Another controller is responsible for that.
 */
class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSendResetLinkForm(Request $request)
    {
        return view('centurion::auth/passwords/email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Deltoss\Centurion\Http\Requests\Auth\SendResetLinkEmailRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    {
        $email = $request->input('email');
        $credentials = [
            'login' => $email,
        ];
        $user = Sentinel::findByCredentials($credentials);

        Reminder::removeExpired(); // Clean up expired reminders
        if ($user)
        {
            $reminder = Reminder::exists($user);
            if (!$reminder)
                $reminder = Reminder::create($user);
            Mail::to($user->email)->send(new ForgotPassword($user, $reminder));
        }

        return redirect()->route('forgot_password.email_sent', ['email' => $email]);
    }

    /**
     * Shows confirmation that the mail has been sent
     *
     * @param \Illuminate\Http\Request $request
     * @param string $email
     * @return Response
     */
    public function passwordResetEmailSent(Request $request, $email)
    {
        return view('centurion::auth/passwords/email_sent', compact('email'));
    }
}
