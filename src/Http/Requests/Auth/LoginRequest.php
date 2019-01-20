<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class LoginRequest extends CenturionFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required',
            'password' => 'required',
        ];
    }
}
