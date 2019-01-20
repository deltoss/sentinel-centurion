<?php

namespace Deltoss\Centurion\Http\Controllers\Abilities\Users;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Abilities\Users\SyncUsersRequest;

class AbilityUserController extends Controller
{
    /**
     * Show list of users that has the specified permission, and can assign users from there.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignUsers(Request $request, $id)
    {
        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);
        
        // Associative array which we construct using the user abilities, and user permissions
        // This will be passed to the view
        $userPermissions = array(); 

        $users = Sentinel::getUserRepository()->orderBy('email', 'ASC')->get();

        foreach($users as $user)
        {
            $allowed = null;
            $existingUserForAbility = array_first($ability->users, function ($value, $key) use($user) {
                return $value->id == $user->id;
            });
            if ($existingUserForAbility)
            {
                $allowed = $existingUserForAbility->user_permission->allowed;
            }
                
            $userPermissions[] = ['user' => $user, 'allowed' => $allowed];
        }

        return view('centurion::abilities/users/assign', compact('ability', 'userPermissions'));
    }

    /**
     * Sync users for a permission.
     *
     * @param \Deltoss\Centurion\Http\Requests\Abilities\Users\SyncUsersRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncUsers(SyncUsersRequest $request, $id)
    {
        $acceptedUsers = $request->input('accepted_users');
        $deniedUsers = $request->input('denied_users');
        $nullUsers = $request->input('null_users');

        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);

        $users = array();
        if ($acceptedUsers)
        {
            foreach($acceptedUsers as $acceptedUserId)
            {
                $users[$acceptedUserId] = [ 'allowed' => true ];
            }
        }
        if ($deniedUsers)
        {
            foreach($deniedUsers as $deniedUserId)
            {
                $users[$deniedUserId] = [ 'allowed' => false ];
            }
        }

        $ability->users()->sync($users);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::permissions.labels.update_users_success', ['name' => $ability->name]));
        // redirect
        return redirect()->route('abilities.index');
    }
}
