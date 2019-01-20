<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class ResetRequest extends CenturionFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'user_id' => 'required',
            'password' => 'required|confirmed|min:6'
        ];
    }
}
