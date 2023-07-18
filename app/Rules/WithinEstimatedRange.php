<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WithinEstimatedRange implements Rule
{
    protected $estimatedReading;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($estimatedReading)
    {
        $this->estimatedReading = $estimatedReading;
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
        // Calculate the minimum range
        $minRange = $this->estimatedReading - ($this->estimatedReading * 0.25);
        // Calculate the maximum range
        $maxRange = $this->estimatedReading + ($this->estimatedReading * 0.25);

        //Pass if bigger than min range, and less than max range
        return ($value >= $minRange) && ($value <= $maxRange);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The meter reading must be within 25% of the estimated reading';
    }
}