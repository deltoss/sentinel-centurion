<?php

namespace Deltoss\Centurion\Http\Controllers\Users\Abilities;

use Sentinel;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Users\Abilities\SyncPermissionsRequest;

class UserAbilityController extends Controller
{
    /**
     * Show list of permissions for a user, and can assign permissions from there.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions(Request $request, $id)
    {
        $user = Sentinel::getUserRepository()->with('abilities')->where('id', $id)->first();
        if(!$user)
            abort(404);

        $abilities = Sentinel::getAbilityRepository()->query()->join('ability_categories', 'abilities.ability_category_id', '=', 'ability_categories.id')
                     ->select(['abilities.id as id','abilities.name as name', 'abilities.slug as slug', 'ability_categories.name as category'])
                     ->orderBy('category', 'asc')
                     ->orderBy('name', 'asc')
                     ->get();
        $groupedAbilities = $abilities->groupBy('category');

        // Associative array which we construct using the user abilities, and user permissions
        // This will be passed to the view
        $groupedUserPermissions = array(); 
        foreach($groupedAbilities as $abilityCategory => $abilities)
        {
            foreach($abilities as $ability)
            {
                $allowed = null;
                $existingAbilityForUser = array_first($user->abilities, function ($value, $key) use($ability) {
                    return $value->id == $ability->id;
                });
                if ($existingAbilityForUser)
                    $allowed = $existingAbilityForUser->permission->allowed;
                
                $groupedUserPermissions[$abilityCategory][] = ['ability' => $ability, 'allowed' => $allowed];
            }
        }

        return view('centurion::users/abilities/assign', compact('user', 'groupedUserPermissions'));
    }

    /**
     * Sync permissions for a user.
     *
     * @param \Deltoss\Centurion\Http\Requests\Users\Abilities\SyncPermissionsRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncPermissions(SyncPermissionsRequest $request, $id)
    {
        $acceptedPermissions = $request->input('accepted_permissions');
        $deniedPermissions = $request->input('denied_permissions');
        $nullPermissions = $request->input('null_permissions');
        
        $user = Sentinel::findUserById($id);
        if (!$user)
            abort(404);

        $permissions = array();
        if ($acceptedPermissions)
        {
            foreach($acceptedPermissions as $acceptedPermissionId)
            {
                $permissions[$acceptedPermissionId] = [ 'allowed' => true ];
            }
        }
        if ($deniedPermissions)
        {
            foreach($deniedPermissions as $deniedPermissionId)
            {
                $permissions[$deniedPermissionId] = [ 'allowed' => false ];
            }
        }

        $user->abilities()->sync($permissions);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::users.labels.update_permissions_success', ['email' => $user->email]));
        // redirect
        return redirect()->route('users.index');
    }
}
