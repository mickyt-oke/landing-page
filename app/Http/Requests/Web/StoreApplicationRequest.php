<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'passport_number' => ['required', 'string', 'max:32'],
            'nationality' => ['required', 'string', 'max:120'],
            'visa_category' => ['required', 'string', 'max:120'],
            'arrival_date' => ['required', 'date', 'before_or_equal:today'],
            'applicant_note' => ['nullable', 'string', 'max:5000'],

            'passport_data_page' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'entry_visa' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'entry_stamp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'return_ticket' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
