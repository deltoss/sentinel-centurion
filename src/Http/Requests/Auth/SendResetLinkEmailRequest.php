<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class SendResetLinkEmailRequest extends CenturionFormRequest
{
    public function withValidator($validator)
    {   
        $validator->sometimes(['g-recaptcha-response'], 'required', function ($input) {
            return config('captcha.secret') && config('captcha.sitekey');
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
