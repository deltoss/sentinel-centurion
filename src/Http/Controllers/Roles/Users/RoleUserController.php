<?php

namespace Deltoss\Centurion\Http\Controllers\Roles\Users;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Deltoss\Centurion\Http\Requests\Roles\Users\SyncUsersRequest;

class RoleUserController extends Controller
{
    /**
     * Show list of users of the role, and can assign users from there.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignUsers(Request $request, $id)
    {
        $role = Sentinel::findRoleById($id);
        if(!$role)
            abort(404);
        $assignedUsers = $role->users()->orderBy('email')->get();

        $assignedUserIds = $assignedUsers->map(function ($item, $key) {
            return $item->id;
        });

        $allUsers = Sentinel::getUserRepository()->orderBy('email')->get();
        $availableUsers = $allUsers->filter(function ($user) use ($assignedUserIds) {
            $isAssignedUser = $assignedUserIds->contains($user->id);
            return !$isAssignedUser;
        });

        return view('centurion::roles/users/assign', compact('role', 'assignedUsers', 'availableUsers'));
    }

    /**
     * Sync users for a role.
     *
     * @param \Deltoss\Centurion\Http\Requests\Roles\Users\SyncUsersRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function syncUsers(SyncUsersRequest $request, $id)
    {
        $assignedUsers = $request->input('assigned_users');

        $role = Sentinel::findRoleById($id);
        if (!$role)
            abort(404);
        
        $role->users()->sync($assignedUsers);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::roles.labels.update_users_success', ['name' => $role->name]));
        // redirect
        return redirect()->route('roles.index');
    }
}
