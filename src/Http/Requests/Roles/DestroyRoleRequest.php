<?php

namespace Deltoss\Centurion\Http\Requests\Roles;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class DestroyRoleRequest extends CenturionFormRequest
{
    public $role;

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;

            // Get id from route
            $id = null;
            $possibleParameterNames = [
                'id',
                'Id',
                'ID',
                'role',
                'roleId',
                'roleID',
                'Role',
                'RoleId',
                'RoleID',
            ];
            foreach($possibleParameterNames as $possibleParameterName)
            {
                $id = $this->route($possibleParameterName);
                if ($id)
                    break;
            }

            $role = Sentinel::findRoleById($id);
            if(!$role)
                abort(404);
            $this->role = $role;
            
            if ($role->users()->count() > 0)
                $validator->errors()->add('Users Exists', trans('centurion::validation.roles.assigned_users'));
        });
    }
}