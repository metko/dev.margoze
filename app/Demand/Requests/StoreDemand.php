<?php

namespace App\Demand\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemand extends FormRequest
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
            'title' => 'required|min:1',
            'description' => 'required|min:1',
            'content' => 'required|min:1',
            //'postal' => 'required|numeric|digits:5',
            // 'budget' => 'nullable',
            'category_id' => 'required|numeric|exists:categories,id',
            'sector_id' => 'required|numeric|exists:sectors,id',
            'sector_id' => 'required|numeric|exists:sectors,id',
            'sector_id' => 'required|numeric|exists:sectors,id',
            'status' => 'nullable|string',
            'be_done_at' => 'required|date',
        ];
    }
}
