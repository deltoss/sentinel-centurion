<?php

namespace Deltoss\Centurion\Http\Controllers\Users\Roles;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Users\Roles\SyncRolesRequest;

class UserRoleController extends Controller
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
        $user = Sentinel::findUserById($id);
        if(!$user)
            abort(404);
        $assignedRoles = $user->roles()->orderBy('name')->get();

        $assignedRoleIds = $assignedRoles->map(function ($item, $key) {
            return $item->id;
        });

        $allRoles = Sentinel::getRoleRepository()->orderBy('name')->get();
        $availableRoles = $allRoles->filter(function ($role) use ($assignedRoleIds) {
            $isAssignedRole = $assignedRoleIds->contains($role->id);
            return !$isAssignedRole;
        });

        return view('centurion::users/roles/assign', compact('user', 'assignedRoles', 'availableRoles'));
    }

    /**
     * Sync roles for a user.
     *
     * @param \Deltoss\Centurion\Http\Requests\Users\Roles\SyncRolesRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncRoles(SyncRolesRequest $request, $id)
    {
        $assignedRoles = $request->input('assigned_roles');

        $user = Sentinel::findUserById($id);
        if (!$user)
            abort(404);
        
        $user->roles()->sync($assignedRoles);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::users.labels.update_roles_success', ['email' => $user->email]));
        // redirect
        return redirect()->route('users.index');
    }
}
