<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\MeterReading;
use App\Services\MeterReadingService;

class WithinEstimatedRange implements Rule
{
    private $user;
    private $meter;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user, $meter)
    {
        $this->user = $user;
        $this->meter = $meter;
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
        //Check for other meter readings for this user and this meter
        //Excules current meter reading
        $otherMeterReadings = MeterReading::where('meter_reading_id', '!=', $this->meter_reading_id)
            ->where('user_id', $this->user->id)
            ->where('meter_id', $this->meter->meter_id)
            ->count();

        if ($otherMeterReadings >= 1) {
            $estimatedMeterReading = MeterReadingService::getEstimatedMeterReading($this->meter, $this->date);
            // Calculate the minimum range
            $minRange = $this->estimatedMeterReading - ($this->estimatedMeterReading * 0.25);
            // Calculate the maximum range
            $maxRange = $this->estimatedMeterReading + ($this->estimatedMeterReading * 0.25);

            //Pass if bigger than min range, and less than max range
            return ($value >= $minRange) && ($value <= $maxRange);
        }
        //If no other meter readings, return true
        return true;
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
