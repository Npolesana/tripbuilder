<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateOneYearFromToday implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $date = \DateTime::createFromFormat('Y-m-d', $value);

        // Get today's date
        $today = new \DateTime();

        // Add one year to today's date
        $oneYearFromToday = $today->modify('+1 year');

        // Check if the input date is less than one year from today
        return $date < $oneYearFromToday;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a date less than one year from today.';
    }
}
