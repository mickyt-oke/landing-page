@extends('partials.header')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 24px 16px;">
    <h1 style="margin-bottom: 8px;">Submit an Application</h1>
    <p style="margin-top: 0; color: #6b7280;">Please fill in the form below and upload the required documents.</p>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 14px; border-radius: 8px; margin: 16px 0;">
            <strong>There were some problems with your submission:</strong>
            <ul style="margin: 8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('web.applications.store') }}" enctype="multipart/form-data" style="margin-top: 18px;">
        @csrf

        <div style="display: grid; gap: 14px;">
            <div>
                <label for="full_name" style="display:block; font-weight: 600; margin-bottom: 6px;">Full name</label>
                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div>
                <label for="passport_number" style="display:block; font-weight: 600; margin-bottom: 6px;">Passport number</label>
                <input id="passport_number" name="passport_number" type="text" value="{{ old('passport_number') }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div>
                <label for="nationality" style="display:block; font-weight: 600; margin-bottom: 6px;">Nationality</label>
                <input id="nationality" name="nationality" type="text" value="{{ old('nationality') }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div>
                <label for="visa_category" style="display:block; font-weight: 600; margin-bottom: 6px;">Visa category</label>
                <input id="visa_category" name="visa_category" type="text" value="{{ old('visa_category') }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div>
                <label for="arrival_date" style="display:block; font-weight: 600; margin-bottom: 6px;">Arrival date</label>
                <input id="arrival_date" name="arrival_date" type="date" value="{{ old('arrival_date') }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div>
                <label for="applicant_note" style="display:block; font-weight: 600; margin-bottom: 6px;">Applicant note (optional)</label>
                <textarea id="applicant_note" name="applicant_note" rows="4"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px;">{{ old('applicant_note') }}</textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 6px 0;">

            <div>
                <label for="passport_data_page" style="display:block; font-weight: 600; margin-bottom: 6px;">Passport data page</label>
                <input id="passport_data_page" name="passport_data_page" type="file" required
                    style="width: 100%; padding: 8px 0;">
            </div>

            <div>
                <label for="entry_visa" style="display:block; font-weight: 600; margin-bottom: 6px;">Entry visa</label>
                <input id="entry_visa" name="entry_visa" type="file" required style="width: 100%; padding: 8px 0;">
            </div>

            <div>
                <label for="entry_stamp" style="display:block; font-weight: 600; margin-bottom: 6px;">Entry stamp</label>
                <input id="entry_stamp" name="entry_stamp" type="file" required style="width: 100%; padding: 8px 0;">
            </div>

            <div>
                <label for="return_ticket" style="display:block; font-weight: 600; margin-bottom: 6px;">Return ticket</label>
                <input id="return_ticket" name="return_ticket" type="file" required style="width: 100%; padding: 8px 0;">
            </div>

            <div style="display:flex; gap: 10px; margin-top: 8px;">
                <button type="submit" style="background:#111827; color:#fff; border:0; border-radius: 8px; padding: 10px 14px; cursor:pointer;">
                    Submit application
                </button>
                <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; padding: 10px 14px; border-radius: 8px; border: 1px solid #d1d5db; color:#111827; text-decoration:none;">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
