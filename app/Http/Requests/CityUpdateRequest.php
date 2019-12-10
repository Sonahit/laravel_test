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
            'startHours' => ['string', 'required', 'lt:endHours', 'max:24'],
            'endHours' => ['string', 'required', 'gt:startHours', 'max:24'],
        ];
    }

    public function message()
    {
        return [
            'startHours.require' => 'Company start hours is required',
            'end.require' => 'Company end hours is required'
        ];
    }
}
