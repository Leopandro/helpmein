<?php

namespace App\Rules;

use Illuminate\Validation\Rule;

/**
 * Валидация инн
 */
class CheckedCheckboxValueRadio extends Rule
{
    public function validate($attribute, $values, $parameters, $validator): bool
    {
        $validator->addReplacer('arrayChecked',  function ($message, $attribute, $rule, $parameters) {
            return str_replace(':count', $parameters[0], $message);
        });
        $questionNumber = explode('.',$attribute)[1];
        $question = $validator->getData()["questions"][$questionNumber];
        $questionType = $question['type'];
        if ($questionType !== 'radio') {
            return true;
        }

        $checked = 0;
        foreach ($values as $value) {
            if ($value['checkBoxValue'] === true) {
                $checked++;
            }
        }
        if ($checked === 1) {
            return true;
        }
        return false;
    }
}

