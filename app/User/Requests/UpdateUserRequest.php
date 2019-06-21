<?php

namespace App\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'username' => 'required|unique:users|min:4',
            'email' => 'required|unique:users|email',
            'first_name' => 'nullable|min:2|string',
            'first_name' => 'nullable|min:2|string',
            'biography' => 'nullable|min:20|',
            'adress_1' => 'nullable|regex:/[^a-z_\-0-9]/i',
            'adress_2' => 'nullable|regex:/[^a-z_\-0-9]/i',
            'sector' => 'nullable|string|max:10',
            'postal' => 'nullable|numeric',
            'city' => 'nullable|regex:/[^a-z_\-0-9]/i|min:3|max:15',
            'phone_1' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'date_of_birth' => 'nullable|date',
        ];
    }
}
