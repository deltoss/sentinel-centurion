<?php

namespace Deltoss\Centurion\Http\Controllers\Auth;

use Sentinel;
use Activation;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Deltoss\Centurion\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    /**
     * View the login page
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function showLoginForm(Request $request)
    {
        return view('centurion::auth/login');
    }

    /**
     * View the login troubleshooting page
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function showLoginTroubleshootForm(Request $request)
    {
        return view('centurion::auth/login_troubleshoot');
    }

    /**
     * Signs in the user
     *
     * @param Deltoss\Centurion\Http\Requests\LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->input('remember');
        $remember = isset($remember) && $remember == 'on' ? true : false;

        $credentials = [
            // 'login' doesn't correspond to a field in the db.
            // Instead, it's a special array key where
            // if you configured Sentinel login to be possible
            // with various fields (e.g. username and/or email)
            // by overriding it's $loginNames property, then
            // you can pass any fields from $loginNames to
            // the array key 'login'.
            'login'    => $login,
            'password' => $password,
        ];
        
        // Create an errors array which we put our errors into
        $errors = [];
        try {
            $user = Sentinel::authenticate($credentials, $remember);
            if ($user == null)
                $errors['Credentials'] = trans('centurion::validation.account.invalid_credentials');
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $ex) {
            $errors['Locked'] = trans('centurion::validation.account.locked');
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $ex){
            
            $user = Sentinel::findByCredentials($credentials);
            $activation = Activation::exists($user);

            if ($activation == false)
            {
                // If no activation record, it means the account is deactivated
                $errors['Deactivated'] = trans('centurion::validation.account.deactivated');
            }
            else
            {
                // If there is activation record, but its not completed (which caused the activation exception)
                // then it means the account hasn't been activated yet.
                $errors['Unactivated'] = trans('centurion::validation.account.unactivated');
            }
        }

        if (count($errors) > 0) {
            return back()
                ->withErrors($errors) //Flashes errors for next request, to show error messages
                ->withInput(
                    $request->except('password')
                );
        }

        if ( $request->input('redirectUrl') )
            return redirect()->to($request->input('redirectUrl'));
        else
            return redirect('/');
    }

    /**
     * View the log off page
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function logout(Request $request) 
    {
        Sentinel::logout(null, true); // Logs out current user, and destroys any active session
        $request->session()->flash('message', trans('centurion::login.labels.logout_successful'));
        return redirect()->route('login.request');
    }

    /**
     * View the unauthorised page
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function unauthorised(Request $request) 
    {
        return view('centurion::auth/unauthorised');
    }
}
