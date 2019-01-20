<?php

namespace Deltoss\Centurion\Http\Requests\Auth;

use Sentinel;
use Activation;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;
use Illuminate\Support\Facades\Validator;

class ActivateRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;

            // Perform validation for the route parameters
            $rules = [
                'token' => 'required',
                'email' => 'required|email',
            ];
            $data = [
                'email' => $this->route('email'),
                'token' => $this->route('token'),
            ];
            $routeValidator = Validator::make($data, $rules);
            if ($routeValidator->fails())
                $validator->errors()->merge($routeValidator->errors());
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
