<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|phone:' . env('APP_LOCALE'),
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('messages.first_name_required'),
            'first_name.string' => __('messages.first_name_string'),
            'first_name.max' => __('messages.first_name_max'),
            'last_name.required' => __('messages.last_name_required'),
            'last_name.string' => __('messages.last_name_string'),
            'last_name.max' => __('messages.last_name_max'),
            'company_id.required' => __('messages.company_id_required'),
            'company_id.exists' => __('messages.company_id_exists'),
            'email.email' => __('messages.email_address'),
            'email.unique' => __('messages.employee_email_unique'),
            'phone.string' => __('messages.phone_string'),
            'phone.phone' => __('messages.phone_country'),
        ];
    }
}