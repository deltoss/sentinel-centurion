<?php

namespace Deltoss\Centurion\Http\Requests\Abilities;

use Sentinel;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class StoreAbilityRequest extends CenturionFormRequest
{
    public $abilityCategory;
    public $newAbilityCategoryText;

    public function withValidator($validator)
    {
        // if the ability category is not
        // an existing category id, and
        // is a new ability category then
        // go through regex validation
        $validator->sometimes('ability_category', 'regex:/^[a-zA-Z\d\s]+$/u', function ($input) {
            $abilityCategoryIdOrNewCategoryText = $input->ability_category;
            $this->abilityCategory = Sentinel::getAbilityCategoryRepository()->findById($abilityCategoryIdOrNewCategoryText);
            if (!($this->abilityCategory))
            {
                // If it's not an Ability Category ID, then it's a new ability category
                $this->newAbilityCategoryText = trim($abilityCategoryIdOrNewCategoryText);
                return true;
            }
            return false;
        });

        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;

            // Ensure the slug is not taken by others
            $slug = $this->request->get('slug');
            if ($slug)
            {
                $existingAbility = Sentinel::getAbilityRepository()->findBySlug($slug);
                if ($existingAbility)
                    $validator->errors()->add('slug', trans('centurion::validation.permissions.slug_exists', ['slug' => $slug]));
            }

            // Ensure the name is not taken by others
            $name = $this->request->get('name');
            if ($name)
            {
                $existingAbility = Sentinel::getAbilityRepository()->findByName($name);
                if ($existingAbility)
                    $validator->errors()->add('name', trans('centurion::validation.permissions.name_exists', ['name' => $name]));
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
            'ability_category' => 'required',
            'name' => 'required|regex:/^[\pL\s]+$/u', // Allows for any letter and whitespace
            'slug' => 'required|regex:/^[a-z\-]+$/u', // Allows for lower-case letters, and hyphen
        ];
    }
}
