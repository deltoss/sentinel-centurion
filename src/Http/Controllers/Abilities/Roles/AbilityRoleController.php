<?php

namespace Deltoss\Centurion\Http\Controllers\Abilities\Roles;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Abilities\Roles\SyncRolesRequest;

class AbilityRoleController extends Controller
{
    /**
     * Show list of roles of the user, and can assign roles from there.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignRoles(Request $request, $id)
    {
        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);
        
        // Associative array which we construct using the role abilities, and role permissions
        // This will be passed to the view
        $rolePermissions = array(); 

        $roles = Sentinel::getRoleRepository()->orderBy('name', 'ASC')->get();

        foreach($roles as $role)
        {
            $allowed = null;
            $existingRoleForAbility = array_first($ability->roles, function ($value, $key) use($role) {
                return $value->id == $role->id;
            });
            if ($existingRoleForAbility)
            {
                $allowed = $existingRoleForAbility->role_permission->allowed;
            }
                
            $rolePermissions[] = ['role' => $role, 'allowed' => $allowed];
        }

        return view('centurion::abilities/roles/assign', compact('ability', 'rolePermissions'));
    }

    /**
     * Sync roles for a ability.
     *
     * @param \Deltoss\Centurion\Http\Requests\Abilities\Roles\SyncRolesRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncRoles(SyncRolesRequest $request, $id)
    {
        $acceptedRoles = $request->input('accepted_roles');
        $deniedRoles = $request->input('denied_roles');
        $nullRoles = $request->input('null_roles');

        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);

        $roles = array();
        if ($acceptedRoles)
        {
            foreach($acceptedRoles as $acceptedRoleId)
            {
                $roles[$acceptedRoleId] = [ 'allowed' => true ];
            }
        }
        if ($deniedRoles)
        {
            foreach($deniedRoles as $deniedRoleId)
            {
                $roles[$deniedRoleId] = [ 'allowed' => false ];
            }
        }

        $ability->roles()->sync($roles);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::permissions.labels.update_roles_success', ['name' => $ability->name]));
        // redirect
        return redirect()->route('abilities.index');
    }
}
