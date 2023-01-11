<?php

namespace App\Rules;

use Illuminate\Validation\Rule;

/**
 * Валидация инн
 */
class CheckedCheckboxValue extends Rule
{
    public function validate($attribute, $values, $parameters, $validator): bool
    {
        $validator->addReplacer('arrayChecked',  function ($message, $attribute, $rule, $parameters) {
            return str_replace(':count', $parameters[0], $message);
        });
        $checked = false;
        foreach ($values as $value) {
            if ($value['checkBoxValue'] === true) {
                $checked = true;
            }
        }
        if (!$checked) {
            return false;
        }
        return true;
    }
}
