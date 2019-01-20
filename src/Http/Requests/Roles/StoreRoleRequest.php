<?php

namespace Deltoss\Centurion\Http\Requests\Roles;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class StoreRoleRequest extends CenturionFormRequest
{
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;

            $slug = $this->request->get('slug');
            // Ensure the slug is not taken by others
            if ($slug)
            {
                $existingRole = Sentinel::findRoleBySlug($slug);
                if ($existingRole)
                    $validator->errors()->add('slug', trans('centurion::validation.roles.slug_exists', ['slug' => $slug]));
            }

            // Ensure the name is not taken by others
            $name = $this->request->get('name');
            if ($name)
            {
                $existingRole = Sentinel::findRoleByName($name);
                if ($existingRole)
                    $validator->errors()->add('name', trans('centurion::validation.roles.name_exists', ['name' => $name]));
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u', // Allows for any letter, whitespace, and hyphen
            'slug' => 'nullable|regex:/^[a-z\-]+$/u', // Allows for lower-case letters, and hyphen
        ];
    }
}
