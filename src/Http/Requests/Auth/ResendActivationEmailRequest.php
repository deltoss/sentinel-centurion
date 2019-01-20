<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Activation;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class ResendActivationEmailRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {   
        $validator->sometimes(['g-recaptcha-response'], 'required', function ($input) {
            return config('captcha.secret') && config('captcha.sitekey');
        });

        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            $credentials = [
                'login' => $this->request->get('email'),
            ];
            $user = Sentinel::findByCredentials($credentials);
            
            if ($user)
            {
                if (Activation::completed($user))
                    $validator->errors()->add('Account Already Activated', trans('centurion::validation.account.already_activated'));
                else if (!Activation::exists($user))
                    $validator->errors()->add('Account Deactivated', trans('centurion::validation.account.deactivated'));
            }
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
            'email' => 'required|email',
            'g-recaptcha-response' => 'nullable|captcha'
        ];
    }
}
