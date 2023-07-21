<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WithinEstimatedRange;

class UpdateMeterReadingRequest extends FormRequest
{
    private $user;
    private $meter;

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setMeter($meter)
    {
        $this->meter = $meter;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'date' => ['required', 'date'],
            'reading' => ['required', 'numeric'],
        ];

        //Validate that meter reading being submitted is within 25% of an estimated reading
        if ($this->user && $this->meter) {
            $rules['reading'][] = new WithinEstimatedRange($this->user, $this->meter);
        }

        return $rules;
    }
}
