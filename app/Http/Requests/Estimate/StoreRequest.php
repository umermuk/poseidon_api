<?php

namespace App\Http\Requests\Estimate;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'building_type_id' => 'required|exists:building_types,id',
            'steep_roof_id' => 'required|exists:steep_roofs,id',
            'currently_roof_id' => 'required|exists:roofs,id',
            'installed_roof_id' => 'required|exists:roofs,id',
            'when_start' => 'required',
            'interested_financing' => 'required',
            'address' => 'required',
            'roof_size' => 'required|numeric',
            'about' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'type' => 'required|in:estimate,proposal',
        ];
    }

    public function attributes(): array
    {
        return [
            'building_type_id' => 'Building Type',
            'steep_roof_id' => 'How steep is your roof?',
            'currently_roof_id' => 'What is currently on your roof?',
            'installed_roof_id' => 'What would you like to have installed?',
            'when_start' => 'When do you want to start?',
            'interested_financing' => 'Are you interested in financing?',
            'address' => 'Address',
            'roof_size' => 'Roof Size',
            'about' => 'Project About',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
}
