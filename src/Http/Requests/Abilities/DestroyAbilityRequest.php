<?php

namespace Deltoss\Centurion\Http\Requests\Abilities;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class DestroyAbilityRequest extends CenturionFormRequest
{
    public $ability;

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
                'ability',
                'abilityId',
                'abilityID',
                'Ability',
                'AbilityId',
                'AbilityID',
                'permission',
                'permissionId',
                'permissionID',
                'Permission',
                'PermissionId',
                'PermissionID',
            ];
            foreach($possibleParameterNames as $possibleParameterName)
            {
                $id = $this->route($possibleParameterName);
                if ($id)
                    break;
            }

            $ability = Sentinel::getAbilityRepository()->findById($id);
            if(!$ability)
                abort(404);
            $this->ability = $ability;
            
            if ($ability->roles()->count() > 0)
                $validator->errors()->add('Roles Exists', trans('centurion::validation.permissions.assigned_roles'));
            if ($ability->users()->count() > 0)
                $validator->errors()->add('Users Exists', trans('centurion::validation.permissions.assigned_users'));
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
