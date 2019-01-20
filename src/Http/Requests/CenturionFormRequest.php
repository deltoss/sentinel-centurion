<?php

namespace Deltoss\Centurion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sentinel;

/**
 * Centurion FormRequest class that grants additional features
 * specific to Centurion. It automatically load additional
 * translations (i.e. validation messages and attribute names)
 * from the Centurion translations
 */
class CenturionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * By default, it returns false.
     * If its a request that doesn't require authorization
     * Set to return true.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * Get the validator instance for the request.
     * This method is overridden from:
     *   Illuminate\Foundation\Http\FormRequest
     * 
     * The override is to put additional
     * custom attribute names and error 
     * messages from the Centurion 
     * translation files.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->setAttributeNames(array_merge($validator->customAttributes, $this->getCenturionAttributeNames()));
        $validator->setCustomMessages(array_merge($validator->customMessages, $this->getCenturionCustomMessages()));
        return $validator;
    }

    /**
     * Load the attributes translations
     * from the centurion translation
     * file "validation".
     * 
     * Attributes are used to replace
     * the text inside you validation
     * error messages for nicer names.
     */
    protected function getCenturionAttributeNames()
    {
        $attributes = [];
        $translationPath = 'centurion::validation.attributes';
        $attributesFromTranslations = trans($translationPath);
        if (is_array($attributesFromTranslations))
        {
            foreach ($attributesFromTranslations as $attributesTranslationKey => $attributesTranslationValue)
            {
                $attributes[$attributesTranslationKey] = $attributesTranslationValue;
            }
        }
        return $attributes;
    }

    /**
     * Load the custom error 
     * messages from the centurion 
     * translations file "validation".
     */
    protected function getCenturionCustomMessages()
    {
        $messages = [];
        $translationPath = 'centurion::validation.custom';
        $messagesFromTranslations = trans($translationPath);
        if (is_array($messagesFromTranslations))
            $messages = $this->flattenAssociativeArray($messagesFromTranslations);
        return $messages;
    }

    protected function flattenAssociativeArray($array, $keyDelimiter = '.') 
    {
        $flattennedArray = [];
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $childFlattennedArray = $this->flattenAssociativeArray($value, $keyDelimiter);
                foreach ($childFlattennedArray as $childFlattennedItemKey => $childFlattennedItemValue)
                {
                    $flattennedArray[$key . $keyDelimiter . $childFlattennedItemKey] = $childFlattennedItemValue;
                }
            }
            else
                $flattennedArray[$key] = $value;
        }
        return $flattennedArray;
    }

    /**
     * If you wish to customize the format of the validation 
     * errors that are flashed to the session when validation 
     * fails, override the formatErrors
     */
    protected function formatErrors(Validator $validator)
    {
        //The default format
        return $validator->errors()->all();
    }
}
