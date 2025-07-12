<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:companies,email,' . $this->route('company'),
            'logo_path' => 'nullable|image|dimensions:min_width=100,min_height=100|max:3072',
            'website' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'logo_path.dimensions' => __('messages.logo_path_dimensions'),
            'logo_path.image' => __('messages.logo_path_image'),
            'logo_path.max' => __('messages.logo_path_max'),
        ];
    }
}
