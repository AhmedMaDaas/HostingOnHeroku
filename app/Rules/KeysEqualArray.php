<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KeysEqualArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $array = [];

    public function __construct($array = [])
    {
        $this->array = $array;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return count(array_diff(array_keys($value), array_values($this->array))) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('admin.keys_equal_array_error', ['attribute' => ':attribute']);
    }
}
