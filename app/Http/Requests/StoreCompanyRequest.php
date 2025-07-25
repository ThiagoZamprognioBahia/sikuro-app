<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:companies,email',
            'logo_path' => 'nullable|image|dimensions:min_width=100,min_height=100|max:3072',
            'website' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'name.max' => __('messages.name_max'),
            'email.email' => __('messages.email_address'),
            'email.unique' => __('messages.company_email_unique'),
            'logo_path.dimensions' => __('messages.logo_path_dimensions'),
            'logo_path.image' => __('messages.logo_path_image'),
            'logo_path.max' => __('messages.logo_path_max'),
            'website.url' => __('messages.website_url'),
        ];
    }
}