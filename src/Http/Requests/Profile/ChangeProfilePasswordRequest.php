<?php

namespace Deltoss\Centurion\Http\Requests\Profile;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class ChangeProfilePasswordRequest extends CenturionFormRequest
{
    public $user;
    public $hasher;

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            $this->user = Sentinel::getUser();
            if (!($this->user))
                abort(404);
            $this->hasher = Sentinel::getHasher();
            
            $oldPassword = $this->request->get('old_password');

            if (!$this->hasher->check($oldPassword, $this->user->password))
                $validator->errors()->add('Incorrect Old Password', trans('centurion::validation.account.incorrect_old_password'));
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
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ];
    }
}
