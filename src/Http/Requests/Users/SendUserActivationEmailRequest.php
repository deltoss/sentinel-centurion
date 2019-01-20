<?php

namespace Deltoss\Centurion\Http\Requests\Users;

use Sentinel;
use Activation;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class SendUserActivationEmailRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            // Get id from route
            $id = null;
            $possibleParameterNames = ['id', 'userId', 'user', 'Id', 'User', 'UserId', 'ID', 'userID', 'UserID'];
            foreach($possibleParameterNames as $possibleParameterName)
            {
                $id = $this->route($possibleParameterName);
                if ($id)
                    break;
            }
            
            $this->user = Sentinel::findById($id);
            if (!($this->user))
                abort(404);
            $activated = Activation::completed($this->user);
            if ($activated)
                $validator->errors()->add('Already Activated', trans('centurion::validation.account.already_activated'));
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