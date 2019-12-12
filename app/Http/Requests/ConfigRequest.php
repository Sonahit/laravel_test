<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'INT_VAL' => ['nullable', 'integer'],
            'BOOL_VAL' => ['nullable', 'boolean'],
            'DATE_VAL' => ['nullable', 'date'],
            'STRING_VAL' => ['nullable', 'string']
        ];
    }
    public function message()
    {
        return [
            'name.required' => 'Configuration name is required'
        ];
    }
}
