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
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'content' => 'required|min:20',
            'postal' => 'required|numeric|digits:5',
            'location' => 'required',
            'budget' => 'nullable',
            'category_id' => 'required|numeric|exists:demand_categories,id',
            'sector_id' => 'required|numeric|exists:demand_sectors,id',
            'status_id' => 'nullable|numeric|exists:demand_status,id',
            'be_done_at' => 'required|date',
        ];
    }
}
