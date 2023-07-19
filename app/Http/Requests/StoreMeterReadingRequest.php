<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WithinEstimatedRange;

class StoreMeterReadingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            //Validate that meter reading being submitted is within 25% of an estimated reading
            'reading' => ['required', 'interger', new WithinEstimatedRange($estimatedReading)],
        ];
    }
}
