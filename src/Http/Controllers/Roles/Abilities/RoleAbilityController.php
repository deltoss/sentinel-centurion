<?php

namespace Deltoss\Centurion\Http\Controllers\Roles\Abilities;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Roles\Abilities\SyncPermissionsRequest;

class RoleAbilityController extends Controller
{
    /**
     * Show list of permissions for a role,
     * and can assign permissions from there.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions(Request $request, $id)
    {
        $role = Sentinel::getRoleRepository()->with('abilities')->where('id', $id)->first();
        if(!$role)
            abort(404);

        // Note depending on your requirements, you can use Eloquent join statements
        $abilities = Sentinel::getAbilityRepository()->query()->with('abilityCategory')
                     ->get()
                     ->sortBy(function($ability) {
                         return [$ability->abilityCategory->name, $ability->name];
                     });
        $groupedAbilities = $abilities->groupBy('abilityCategory.name');

        // Associative array which we construct using the role abilities, and role permissions
        // This will be passed to the view
        $groupedRolePermissions = array(); 
        foreach($groupedAbilities as $abilityCategory => $abilities)
        {
            foreach($abilities as $ability)
            {
                $allowed = null;
                $existingAbilityForRole = array_first($role->abilities, function ($value, $key) use($ability) {
                    return $value->id == $ability->id;
                });
                if ($existingAbilityForRole)
                    $allowed = $existingAbilityForRole->permission->allowed;
                
                $groupedRolePermissions[$abilityCategory][] = ['ability' => $ability, 'allowed' => $allowed];
            }
        }

        return view('centurion::roles/abilities/assign', compact('role', 'groupedRolePermissions'));
    }

    /**
     * Sync permissions for a role.
     *
     * @param \Deltoss\Centurion\Http\Requests\Roles\Abilities\SyncPermissionsRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncPermissions(SyncPermissionsRequest $request, $id)
    {
        $acceptedPermissions = $request->input('accepted_permissions');
        $deniedPermissions = $request->input('denied_permissions');
        $nullPermissions = $request->input('null_permissions');

        $role = Sentinel::findRoleById($id);
        if (!$role)
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

        $role->abilities()->sync($permissions);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::roles.labels.update_permissions_success', ['name' => $role->name]));
        // redirect
        return redirect()->route('roles.index');
    }
}
