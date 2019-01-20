<?php

namespace Deltoss\Centurion\Http\Controllers\Users;

use Sentinel;
use Activation;
use Reminder;
use Mail;
use Deltoss\Centurion\Mail\Auth\ActivateAccount;
use Deltoss\Centurion\Mail\Auth\AccountCreated;
use Deltoss\Centurion\Mail\Auth\ForgotPassword;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Users\UpdateUserRequest;
use Deltoss\Centurion\Http\Requests\Users\StoreUserRequest;
use Deltoss\Centurion\Http\Requests\Users\SendUserActivationEmailRequest;
use Deltoss\Centurion\Http\Requests\Users\SendUserResetPasswordEmailRequest;
use Deltoss\Centurion\Http\Requests\Users\DestroyUserRequest;
use Deltoss\Centurion\Http\Requests\Users\ActivateUserRequest;
use Deltoss\Centurion\Http\Requests\Users\DeactivateUserRequest;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $builder = Sentinel::getUserRepository()->orderBy('email');
        $pageSize = $request->input('page_size');
        
        $users = null;
        if ($pageSize)
        {
            $pageSize = min($pageSize, 100); // Dynamic page size from query string, but has an upper limit of 100
        
            // Perform pagination
            $users = $builder->paginate($pageSize);
            // Append the additional parameters for 
            // dynamic parameters (e.g. page_size, etc)
            // to also affect the next/prev page links
            $users->appends('page_size', $pageSize);
        }
        else
        {
            $users = $builder->paginate(); // Use default pagination size setting, defined in model
        }
        return view('centurion::users/index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('centurion::users/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Users\StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {   
        // Create a new user
        // To login, user must be activated

        // Make sure all array keys from the request
        // (i.e. your GET or POST parameters)
        // matches the database fields that 
        // corresponds to your User
        // model's $fillable property.
        // See Eloquent Mass Assignments
        // for more details.
        $credentials = $request->all();
        unset($credentials['add_as']);
        $addAs = $request->input('add_as');
        
        if ($addAs == 1) // Add as deactivated user, and send the activation email to the user
        {
            // Generate a temporary random password, just so sentinel doesn't complain about no password
            $credentials['password'] = str_random(8);
            $user = Sentinel::register($credentials);
            $user->created_by_another_user = true;
            $user->change_password_on_activation = true;
            $user->save();
            $activation = Activation::create($user);

            Mail::to($user->email)->send(new AccountCreated($user, $activation));
        }
        else if ($addAs == 2) // Add as activated user
        {
            $user = Sentinel::registerAndActivate($credentials);   
            $user->created_by_another_user = true;
            $user->save();
        }
        else // Add as deactivated user
        {
            $user = Sentinel::register($credentials);
            $user->created_by_another_user = true;
            $user->save();
        }

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::users.labels.create_success', ['email' => $user->email]));
        // redirect
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = Sentinel::findUserById($id);
        if(!$user)
            abort(404);
        return view('centurion::users/show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = Sentinel::findUserById($id);
        if(!$user)
            abort(404);
        return view('centurion::users/edit', compact('user'));
    }

    /**
     * Updates an existing resource in storage.
     *
     * @param \Deltoss\Centurion\Requests\UpdateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
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

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::users.labels.update_success', ['email' => $originalEmail]));
        // redirect
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Deltoss\Centurion\Requests\DestroyUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyUserRequest $request, $id)
    {
        $user = Sentinel::findUserById($id);
        if(!$user)
            abort(404);
        $user->delete();

        $currentUser = Sentinel::getUser();

        // redirect
        $request->session()->flash('message', trans('centurion::users.labels.delete_success', ['email' => $user->email]));
        if ($id == $currentUser->id) // If deleted account is the logged in user
        {
            Sentinel::logout(null, true); // Destroy session
            return redirect()->route('login.request');
        }

        return redirect()->route('users.index');
    }

    /**
     * Sends the activation email to a user.
     *
     * @param \Deltoss\Centurion\Requests\SendUserActivationEmailRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function sendActivationEmail(SendUserActivationEmailRequest $request, $id) 
    {
        $user = $request->user;
        
        Activation::remove($user);
        $activation = Activation::create($user);
        
        if ($user->created_by_another_user)
            Mail::to($user->email)->send(new AccountCreated($user, $activation));
        else
            Mail::to($user->email)->send(new ActivateAccount($user, $activation));
            
        $request->session()->flash('message', trans('centurion::users.labels.send_activation_email_success', ['email' => $user->email]));
        
        return back();
    }

    /**
     * Sends the forgot password email to a user.
     *
     * @param \Deltoss\Centurion\Requests\SendUserResetPasswordEmailRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function sendResetPasswordEmail(SendUserResetPasswordEmailRequest $request, $id) 
    {
        $user = Sentinel::findById($id);
        
        Reminder::removeExpired(); // Clean up expired reminders
        if ($user)
        {
            $reminder = Reminder::exists($user);
            if (!$reminder)
                $reminder = Reminder::create($user);
            Mail::to($user->email)->send(new ForgotPassword($user, $reminder));
        }
            
        $request->session()->flash('message', trans('centurion::users.labels.send_reset_password_email_success', ['email' => $user->email]));
        
        return back();
    }

    /**
     * Activate a user.
     *
     * @param \Deltoss\Centurion\Requests\ActivateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function activate(ActivateUserRequest $request, $id)
    {
        $user = $request->user;

        // Remove unactivated records which is potentially expired
        while ($unactivatedActivation = Activation::exists($user))
        {
            $unactivatedActivation->delete();
        }

        $activation = Activation::create($user);
        $successfulActivation = Activation::complete($user, $activation->code);
        $errors = new MessageBag();
        if ($successfulActivation)
            $request->session()->flash('message', trans('centurion::users.labels.activate_success', ['email' => $user->email]));
        else
            $errors->add('Activation Failed', trans('centurion::validation.account.user_activation_failed', ['email' => $user->email]));
        
        return back();
    }

    /**
     * Deactivate a user.
     *
     * @param \Deltoss\Centurion\Requests\DeactivateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(DeactivateUserRequest $request, $id)
    {
        $user = $request->user;

        while ($completedActivation = Activation::completed($user))
        {
            $completedActivation->delete();
        }
        while ($unactivatedActivation = Activation::exists($user))
        {
            $unactivatedActivation->delete();
        }

        $request->session()->flash('message', trans('centurion::users.labels.deactivate_success', ['email' => $user->email]));
        $currentUser = Sentinel::getUser();

        if ($id == $currentUser->id) // If deactivated account is the logged in user
        {
            Sentinel::logout(null, true); // Destroy session
            return redirect()->route('login.request');
        }
        return back();
    }
}
