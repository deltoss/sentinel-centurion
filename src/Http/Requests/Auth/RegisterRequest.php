<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class RegisterRequest extends CenturionFormRequest
{
    public function withValidator($validator)
    {
        $validator->sometimes(['g-recaptcha-response'], 'required', function ($input) {
            return config('captcha.secret') && config('captcha.sitekey');
        });

        $validator->after(function ($validator) {
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
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'first_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
            'last_name' => 'required|regex:/^[\pL\s-\'\.]+$/u', // Allows for any letter, whitespace, single quote and dot
            'g-recaptcha-response' => 'nullable|captcha'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'g-recaptcha-response.required'  => trans('centurion::validation.captcha_required'),
            'g-recaptcha-response.captcha'  => trans('centurion::validation.captcha_invalid'),
        ];
    }
}
