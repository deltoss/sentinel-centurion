<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Activation;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class ActivateWithNewPasswordRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            $credentials = [
                'login' => $this->request->get('email'),
            ];
            $user = Sentinel::findByCredentials($credentials);
            if (!$user)
                abort(404);
            else if (Activation::completed($user))
                $validator->errors()->add('Account Already Activated', trans('centurion::validation.account.already_activated'));
            else if (!Activation::exists($user))
                $validator->errors()->add('Account Deactivated', trans('centurion::validation.account.deactivated'));

            $this->user = $user;
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ];
    }
}
