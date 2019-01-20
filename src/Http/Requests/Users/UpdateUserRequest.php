<?php

namespace Deltoss\Centurion\Http\Requests\Users;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class UpdateUserRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            // Get id from route, or the request
            $id = null;
            $possibleParameterNames = ['id', 'userId', 'user', 'Id', 'User', 'UserId', 'ID', 'userID', 'UserID'];
            foreach($possibleParameterNames as $possibleParameterName)
            {
                $id = $this->route($possibleParameterName);
                if (!$id)
                    $id = $this->request->get($possibleParameterName);
                if ($id)
                    break;
            }
            
            $this->user = Sentinel::findById($id);
            if (!($this->user))
                abort(404);
            
            // Assumming the HTML field names matches the login names,
            // and the translation in the register.php file,
            // we perform validation to make sure they are unique
            $userInstance = Sentinel::getUserRepository()->createModel();
            $loginNames = $userInstance->getLoginNames();
            foreach ($loginNames as $loginName)
            {
                $loginValue = $this->request->get($loginName);
                if (!$loginValue || $loginValue == $this->user[$loginName])
                    continue;
                $credentials = [
                    'login' => $loginValue,
                ];
                $user = Sentinel::findByCredentials($credentials);
                if ($user)
                    $validator->errors()->add($loginName, trans('centurion::validation.account.already_exists', ['value' => $loginValue]));
            }
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
            'email' => 'required|email',
            'first_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
            'last_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
        ];
    }
}
