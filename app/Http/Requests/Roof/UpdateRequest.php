<?php

namespace App\Http\Requests\Roof;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => "required|unique:roofs,name," . $this->route('roof')->id,
            'price' => "required|numeric",
            'desc' => "nullable",
            'image' => "nullable",
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => "Name",
            'price' => "Price",
            'desc' => "Description",
            'image' => "Image",
        ];
    }
}
