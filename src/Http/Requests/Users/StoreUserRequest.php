<?php

namespace Deltoss\Centurion\Http\Requests\Users;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class StoreUserRequest extends CenturionFormRequest
{
    public function withValidator($validator)
    {
        // If "add_as" does not have the
        // value of 1, i.e. send activation
        // email, then password is required
        $validator->sometimes('password', 'required', function ($input) {
            return $input->add_as != 1;
        });

        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;

            
            
            // Assumming the HTML field names matches the login names,
            // and the translation in the register.php file,
            // we perform validation to make sure they are unique
            $userInstance = Sentinel::getUserRepository()->createModel();
            $loginNames = $userInstance->getLoginNames();
            foreach ($loginNames as $loginName)
            {
                $loginValue = $this->request->get($loginName);
                if (!$loginValue)
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
            'add_as' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|confirmed|min:6',
            'first_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
            'last_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
        ];
    }
}
