<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
{
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
        return [
            'city' => ['string'],
            'address' => ['string'],
            'startHours' => ['integer', 'required', 'lt:endHours', 'max:24'],
            'endHours' => ['integer', 'required', 'gt:startHours', 'max:24'],
            'bookingInterval'=> ['integer', 'required', 'max:6']
        ];
    }

    public function message()
    {
        return [
            'startHours.required' => 'Company start hours is required',
            'end.required' => 'Company end hours is required'
        ];
    }
}
