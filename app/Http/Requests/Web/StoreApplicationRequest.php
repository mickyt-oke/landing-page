<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        $fullName = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $this->input('surname', '') . ' ' . $this->input('first_name', '') . ' ' . $this->input('other_names', '')
            ) ?? ''
        );

        $this->merge([
            'full_name' => $fullName,
        ]);

        // Handle applicant_note for "Other" option
        if ($this->input('applicant_note') === 'Other' && $this->input('reason_other_text')) {
            $this->merge([
                'applicant_note' => $this->input('reason_other_text'),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'other_names' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['required', 'string', 'max:32'],
            'nationality' => ['required', 'string', 'max:120'],
            'visa_category' => ['required', 'string', 'max:120'],
            'arrival_date' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'applicant_note' => ['nullable', 'string', 'max:5000'],

            'passport_data_page' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'entry_visa' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'entry_stamp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'return_ticket' => ['required', 'file', 'mimes:pdf,jpeg,png', 'max:5120'],
        ];
    }
}
