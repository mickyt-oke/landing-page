@include('partials.header')

<div class="dashboard-content">

    {{-- ── Flash messages delivered as AJAX toasts ────────── --}}
    @if(session('status'))
        <script>window.__flash = window.__flash || []; window.__flash.push({msg: {{ Js::from((string) session('status')) }}, type: 'success'});</script>
    @endif
    @if(session('success'))
        <script>window.__flash = window.__flash || []; window.__flash.push({msg: {{ Js::from((string) session('success')) }}, type: 'success'});</script>
    @endif
    @if(session('error'))
        <script>window.__flash = window.__flash || []; window.__flash.push({msg: {{ Js::from((string) session('error')) }}, type: 'error'});</script>
    @endif
    @if($errors->any())
        @foreach($errors->all() as $error)
                <script>window.__flash = window.__flash || []; window.__flash.push({msg: {{ Js::from((string) $error) }}, type: 'error'});</script>
        @endforeach
    @endif

    {{-- ── Page Header ─────────────────────────────────────── --}}
    <div class="page-header">
        <h2 class="page-title">New Registration</h2>
        <p class="page-subtitle">Complete all four steps to submit your application to the Nigeria Immigration Service.</p>
    </div>

    {{-- ── Wizard Card ─────────────────────────────────────── --}}
    <div class="content-card app-wizard">

        {{-- ── Step Indicator ──────────────────────────────── --}}
        <div class="wizard-header">
            <div class="wizard-steps" role="tablist" aria-label="Application steps">

                <div class="wizard-step active" id="wizStep1"
                     role="tab" aria-selected="true" aria-controls="panel1">
                    <div class="wizard-step-circle">
                        <span class="wizard-step-number" aria-hidden="true">1</span>
                        <i class="fas fa-check wizard-step-check" aria-hidden="true"></i>
                    </div>
                    <div class="wizard-step-label">Biodata</div>
                </div>

                <div class="wizard-step-connector" aria-hidden="true"></div>

                <div class="wizard-step" id="wizStep2"
                     role="tab" aria-selected="false" aria-controls="panel2">
                    <div class="wizard-step-circle">
                        <span class="wizard-step-number" aria-hidden="true">2</span>
                        <i class="fas fa-check wizard-step-check" aria-hidden="true"></i>
                    </div>
                    <div class="wizard-step-label">Travel Info</div>
                </div>

                <div class="wizard-step-connector" aria-hidden="true"></div>

                <div class="wizard-step" id="wizStep3"
                     role="tab" aria-selected="false" aria-controls="panel3">
                    <div class="wizard-step-circle">
                        <span class="wizard-step-number" aria-hidden="true">3</span>
                        <i class="fas fa-check wizard-step-check" aria-hidden="true"></i>
                    </div>
                    <div class="wizard-step-label">Documents</div>
                </div>

                <div class="wizard-step-connector" aria-hidden="true"></div>

                <div class="wizard-step" id="wizStep4"
                     role="tab" aria-selected="false" aria-controls="panel4">
                    <div class="wizard-step-circle">
                        <span class="wizard-step-number" aria-hidden="true">4</span>
                        <i class="fas fa-check wizard-step-check" aria-hidden="true"></i>
                    </div>
                    <div class="wizard-step-label">Review</div>
                </div>

            </div>

            <div class="wizard-progress-bar"
                 role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25"
                 aria-label="Form completion progress">
                <div class="wizard-progress-fill" id="wizardProgress" style="width:25%"></div>
            </div>
        </div>

        {{-- ── Form ─────────────────────────────────────────── --}}
        <form id="registrationForm"
              method="POST"
              action="{{ route('applications.store') }}"
              enctype="multipart/form-data"
              novalidate>
            @csrf

            {{-- ── Step 1: Biodata ─────────────────────────── --}}
            <div id="panel1" class="wizard-panel" role="tabpanel" aria-labelledby="wizStep1">

                <div class="wizard-panel-header">
                    <h3><i class="fas fa-user" aria-hidden="true"></i> Applicant Biodata</h3>
                    <p>Enter your personal details exactly as they appear on your travel document.</p>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="surname">
                            Surname <span class="field-required" aria-label="required">*</span>
                        </label>
                        <input id="surname" name="surname" type="text"
                               class="field-input @error('surname') field-error @enderror"
                               placeholder="As on passport" required autocomplete="family-name"
                               value="{{ old('surname', $prefill['regSurname'] ?? '') }}">
                        @error('surname')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="first_name">
                            First Name <span class="field-required" aria-label="required">*</span>
                        </label>
                        <input id="first_name" name="first_name" type="text"
                               class="field-input @error('first_name') field-error @enderror"
                               placeholder="As on passport" required autocomplete="given-name"
                               value="{{ old('first_name', $prefill['regFirstName'] ?? '') }}">
                        @error('first_name')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="other_names">
                            Other Names <span class="field-optional">(optional)</span>
                        </label>
                        <input id="other_names" name="other_names" type="text"
                               class="field-input @error('other_names') field-error @enderror"
                               placeholder="Middle names, if any" autocomplete="additional-name"
                               value="{{ old('other_names', $prefill['regOtherNames'] ?? '') }}">
                        @error('other_names')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="passport_number">
                            Passport Number <span class="field-required" aria-label="required">*</span>
                        </label>
                        <input id="passport_number" name="passport_number" type="text"
                               class="field-input font-mono @error('passport_number') field-error @enderror"
                               placeholder="e.g. A12345678" required autocomplete="off"
                               value="{{ old('passport_number', $prefill['regPassport'] ?? '') }}">
                        @error('passport_number')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="nationality">
                            Nationality <span class="field-required" aria-label="required">*</span>
                        </label>
                        <select id="nationality" name="nationality"
                                class="field-input @error('nationality') field-error @enderror"
                                required tabindex="0"
                                aria-describedby="nationalityHelp @error('nationality') nationalityError @enderror">
                            <option value="">Select nationality</option>
                            @foreach($nationalities as $nat)
                                <option value="{{ $nat }}"
                                    {{ old('nationality', $prefill['regNationality'] ?? '') === $nat ? 'selected' : '' }}>
                                    {{ $nat }}
                                </option>
                            @endforeach
                        </select>
                        <span id="nationalityHelp" class="field-hint">Select your nationality as shown on your passport.</span>
                        @error('nationality')
                            <span id="nationalityError" class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="passport_expiry">
                            Passport Expiry Date <span class="field-optional">(optional)</span>
                        </label>
                        <input id="passport_expiry" name="passport_expiry" type="date"
                               class="field-input"
                               value="{{ old('passport_expiry') }}">
                    </div>
                </div>

                <div class="wizard-panel-footer">
                    <span></span>
                    <button type="button" class="btn btn-primary wizard-next" data-step="1">
                        Next <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- ── Step 2: Travel Info ─── --}}
            <div id="panel2" class="wizard-panel" role="tabpanel" aria-labelledby="wizStep2" hidden>

                <div class="wizard-panel-header">
                    <h3><i class="fas fa-plane" aria-hidden="true"></i> Travel Information</h3>
                    <p>Provide your visa details and your current address in Nigeria.</p>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="visa_category">
                            Visa Category <span class="field-required" aria-label="required">*</span>
                        </label>
                        <select id="visa_category" name="visa_category"
                                class="field-input @error('visa_category') field-error @enderror"
                                required>
                            <option value="">Select category</option>
                            <option value="SVV" {{ old('visa_category') === 'SVV' ? 'selected' : '' }}>
                                Short Visit Visa (SVV)
                            </option>
                            <option value="TRV" {{ old('visa_category') === 'TRV' ? 'selected' : '' }}>
                                Temporary Residence Visa (TRV)
                            </option>
                        </select>
                        @error('visa_category')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field" id="svvCategory" hidden>
                        <label for="svv_subcat">SVV Sub-category</label>
                        <select id="svv_subcat" name="svv_subcategory" class="field-input">
                            <option value="">Select type</option>
                            <option value="F2A" {{ old('svv_subcategory') === 'F2A' ? 'selected' : '' }}>F2A</option>
                            <option value="F3A" {{ old('svv_subcategory') === 'F3A' ? 'selected' : '' }}>F3A</option>
			    <option value="F3B" {{ old('svv_subcategory') === 'F3B' ? 'selected' : '' }}>F3B</option>
                            <option value="F4A" {{ old('svv_subcategory') === 'F4A' ? 'selected' : '' }}>F4A</option>
			    <option value="F4B" {{ old('svv_subcategory') === 'F4B' ? 'selected' : '' }}>F4B</option>
                            <option value="F4C" {{ old('svv_subcategory') === 'F4C' ? 'selected' : '' }}>F4C</option>
			    <option value="F6A" {{ old('svv_subcategory') === 'F6A' ? 'selected' : '' }}>F6A</option>
                            <option value="F6B" {{ old('svv_subcategory') === 'F6B' ? 'selected' : '' }}>F6B</option>
                            <option value="F7A" {{ old('svv_subcategory') === 'F7A' ? 'selected' : '' }}>F7A</option>
                            <option value="F7B" {{ old('svv_subcategory') === 'F7B' ? 'selected' : '' }}>F7B</option>
 			    <option value="F7C" {{ old('svv_subcategory') === 'F7C' ? 'selected' : '' }}>F7C</option>
                            <option value="F7D" {{ old('svv_subcategory') === 'F7D' ? 'selected' : '' }}>F7D</option>
                            <option value="F7E" {{ old('svv_subcategory') === 'F7E' ? 'selected' : '' }}>F7E</option>
                            <option value="F7F" {{ old('svv_subcategory') === 'F7F' ? 'selected' : '' }}>F7F</option>
                            <option value="F7G" {{ old('svv_subcategory') === 'F7G' ? 'selected' : '' }}>F7G</option>
                            <option value="F7H" {{ old('svv_subcategory') === 'F7H' ? 'selected' : '' }}>F7H</option>
			    <option value="F7I" {{ old('svv_subcategory') === 'F7I' ? 'selected' : '' }}>F7I</option>
                            <option value="F7J" {{ old('svv_subcategory') === 'F7J' ? 'selected' : '' }}>F7J</option>
                            <option value="F7K" {{ old('svv_subcategory') === 'F7K' ? 'selected' : '' }}>F7K</option>
                            <option value="F7L" {{ old('svv_subcategory') === 'F7L' ? 'selected' : '' }}>F7L</option>
			    <option value="F7M" {{ old('svv_subcategory') === 'F7M' ? 'selected' : '' }}>F7M</option>
                            <option value="F9A" {{ old('svv_subcategory') === 'F9A' ? 'selected' : '' }}>F9A</option>
                            <option value="F9B" {{ old('svv_subcategory') === 'F9B' ? 'selected' : '' }}>F9B</option>
			   

                        </select>
                    </div>

                    <div class="form-field" id="trvCategory" hidden>
                        <label for="trv_subcat">TRV Sub-category</label>
                        <select id="trv_subcat" name="trv_subcategory" class="field-input">
                            <option value="">Select type</option>
                            <option value="R1A" {{ old('trv_subcategory') === 'R1A' ? 'selected' : '' }}>R1A</option>
                            <option value="R2A" {{ old('trv_subcategory') === 'R2A' ? 'selected' : '' }}>R2A</option>
			    <option value="R5A" {{ old('trv_subcategory') === 'R5A' ? 'selected' : '' }}>R5A</option>
                            <option value="R6A" {{ old('trv_subcategory') === 'R6A' ? 'selected' : '' }}>R6A</option>
			    <option value="R7A" {{ old('trv_subcategory') === 'R7A' ? 'selected' : '' }}>R7A</option>
                            <option value="R8A" {{ old('trv_subcategory') === 'R8A' ? 'selected' : '' }}>R8A</option>
			    <option value="R9A" {{ old('trv_subcategory') === 'R9A' ? 'selected' : '' }}>R9A</option>
                            <option value="R10" {{ old('trv_subcategory') === 'R10' ? 'selected' : '' }}>R10</option>
			    <option value="R11" {{ old('trv_subcategory') === 'R11' ? 'selected' : '' }}>R11</option>
                        </select>

                    </div>

                    <div class="form-field">
                        <label for="arrival_date">
                            Arrival Date in Nigeria <span class="field-required" aria-label="required">*</span>
                        </label>
                        <input id="arrival_date" name="arrival_date" type="date"
                               class="field-input @error('arrival_date') field-error @enderror"
                               required value="{{ old('arrival_date') }}">
                        @error('arrival_date')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field form-field-full">
                        <label for="address">
                            Street Address in Nigeria <span class="field-required" aria-label="required">*</span>
                        </label>
                        <input id="address" name="address" type="text"
                               class="field-input @error('address') field-error @enderror"
                               placeholder="Street address" required autocomplete="street-address"
                               value="{{ old('address') }}">
                        @error('address')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="state">
                            State <span class="field-required" aria-label="required">*</span>
                        </label>
                        <select id="state" name="state"
                                class="field-input @error('state') field-error @enderror"
                                required>
                            <option value="">Select state</option>
                        </select>
                        @error('state')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="city">
                            City <span class="field-required" aria-label="required">*</span>
                        </label>
                        <select id="city" name="city"
                                class="field-input @error('city') field-error @enderror"
                                required disabled>
                            <option value="">Select city</option>
                        </select>
                        @error('city')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field form-field-full">
                        <label for="applicant_note">
                            Reason for Request <span class="field-optional">(optional)</span>
                        </label>
                        <select id="applicant_note" name="applicant_note" class="field-input">
                            <option value="">Select reason</option>
                            <option value="Flight Cancellation"
                                {{ old('applicant_note') === 'Flight Cancellation' ? 'selected' : '' }}>
                                Flight Cancellation
                            </option>
                            <option value="Delayed Flight"
                                {{ old('applicant_note') === 'Delayed Flight' ? 'selected' : '' }}>
                                Delayed Flight
                            </option>
                            <option value="Other"
                                {{ old('applicant_note') === 'Other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                        <span class="field-hint">Optional note sent with your application.</span>
                    </div>

                    <div class="form-field form-field-full" id="reasonOtherWrapper" hidden>
                        <label for="reason_other_text">
                            Please specify <span class="field-required" aria-label="required">*</span>
                        </label>
                        <textarea id="reason_other_text" name="reason_other_text"
                                  class="field-input" rows="3"
                                  placeholder="Describe your reason in detail">{{ old('reason_other_text') }}</textarea>
                    </div>
                </div>

                <div class="wizard-panel-footer">
                    <button type="button" class="btn btn-outline wizard-prev" data-step="2">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary wizard-next" data-step="2">
                        Next <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- ── Step 3: Documents ────────────────────────── --}}
            <div id="panel3" class="wizard-panel" role="tabpanel" aria-labelledby="wizStep3" hidden>

                <div class="wizard-panel-header">
                    <h3><i class="fas fa-folder-open" aria-hidden="true"></i> Upload Documents</h3>
                    <p>Upload clear scans or photos of each document. Accepted: PDF, JPG, PNG — max 5 MB each.</p>
                </div>

                <div class="upload-grid">
                <!--PASSPORT DATA PAGE -->
                    <div class="upload-field @error('passport_data_page') upload-field-error @enderror">
                        <div class="upload-label">
                            Passport Data Page <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="passport_data_page" id="zone_passport_data_page">
                            <div class="upload-idle">
                                <i class="fas fa-passport upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_passport_data_page" hidden></div>
                        </label>
                        <input id="passport_data_page" name="passport_data_page" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_passport_data_page"
                               data-preview="preview_passport_data_page"
                               aria-label="Passport data page file">
                        @error('passport_data_page')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- END PASSPORT DATA PAGE -->

                    <!-- PASSPORT PHOTO -->
                    <div class="upload-field @error('passport_photo') upload-field-error @enderror">
                        <div class="upload-label">
                            Passport Photo <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="passport_photo" id="zone_passport_photo">
                            <div class="upload-idle">
                                <i class="fas fa-image upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_passport_photo" hidden></div>
                        </label>
                        <input id="passport_photo" name="passport_photo" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_passport_photo"
                               data-preview="preview_passport_photo"
                               aria-label="Passport photo file">
                        @error('passport_photo')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror   
                    </div>
                    <!-- END OF PASSPORT PHOTO -->

                    <!--ENTRY VISA -->
                    <div class="upload-field @error('entry_visa') upload-field-error @enderror">
                        <div class="upload-label">
                            Entry Visa <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="entry_visa" id="zone_entry_visa">
                            <div class="upload-idle">
                                <i class="fas fa-stamp upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_entry_visa" hidden></div>
                        </label>
                        <input id="entry_visa" name="entry_visa" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_entry_visa"
                               data-preview="preview_entry_visa"
                               aria-label="Entry visa file">
                        @error('entry_visa')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- END OF ENTRY VISA -->

                    <!-- ENTRY STAMP -->
                    <div class="upload-field @error('entry_stamp') upload-field-error @enderror">
                        <div class="upload-label">
                            Entry Stamp <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="entry_stamp" id="zone_entry_stamp">
                            <div class="upload-idle">
                                <i class="fas fa-check-square upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_entry_stamp" hidden></div>
                        </label>
                        <input id="entry_stamp" name="entry_stamp" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_entry_stamp"
                               data-preview="preview_entry_stamp"
                               aria-label="Entry stamp file">
                        @error('entry_stamp')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- END OF ENTRY STAMP -->

                    <!-- RETURN TICKET -->
                    <div class="upload-field @error('return_ticket') upload-field-error @enderror">
                        <div class="upload-label">
                            Return Ticket <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="return_ticket" id="zone_return_ticket">
                            <div class="upload-idle">
                                <i class="fas fa-ticket-alt upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_return_ticket" hidden></div>
                        </label>
                        <input id="return_ticket" name="return_ticket" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_return_ticket"
                               data-preview="preview_return_ticket"
                               aria-label="Return ticket file">
                        @error('return_ticket')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- END OF RETURN TICKET -->

                    <!-- PROOF OF CANCELLATION OF FLIGHT -->
                    <div class="upload-field @error('flight_cancellation') upload-field-error @enderror">
                        <div class="upload-label">
                            Proof of Flight Cancellation <span class="field-required" aria-label="required">*</span>
                        </div>
                        <label class="upload-zone" for="flight_cancellation" id="zone_flight_cancellation">
                            <div class="upload-idle">
                                <i class="fas fa-file-alt upload-icon" aria-hidden="true"></i>
                                <span class="upload-cta">Click or drag to upload</span>
                                <span class="upload-hint">PDF · JPG · PNG</span>
                            </div>
                            <div class="upload-preview" id="preview_flight_cancellation" hidden></div>
                        </label>
                        <input id="flight_cancellation" name="flight_cancellation" type="file"
                               class="upload-input" accept=".pdf,.jpg,.jpeg,.png" required
                               data-zone="zone_flight_cancellation"
                               data-preview="preview_flight_cancellation"
                               aria-label="Proof of flight cancellation file">
                        @error('flight_cancellation')
                            <span class="field-msg" role="alert">{{ $message }}</span>
                        @enderror
                        <span class="field-hint">E.g. cancellation email from airline, screenshot of cancelled booking, etc.</span>
                        </div>
                    <!-- END OF PROOF OF CANCELLATION OF FLIGHT -->

                </div>

                <div class="wizard-panel-footer">
                    <button type="button" class="btn btn-outline wizard-prev" data-step="3">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary wizard-next" data-step="3">
                        Next <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- ── Step 4: Review ───────────────────────────── --}}
            <div id="panel4" class="wizard-panel" role="tabpanel" aria-labelledby="wizStep4" hidden>

                <div class="wizard-panel-header">
                    <h3><i class="fas fa-check-circle" aria-hidden="true"></i> Review &amp; Submit</h3>
                    <p>Verify all details are correct before submitting. Click <strong>Previous</strong> to go back and make changes.</p>
                </div>

                <div class="review-grid">
                    <div class="review-card">
                        <div class="review-card-title">
                            <i class="fas fa-user" aria-hidden="true"></i> Personal Information
                        </div>
                        <div class="review-rows">
                            <div class="review-row">
                                <span class="review-label">Surname</span>
                                <span class="review-value" id="reviewSurname">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">First Name</span>
                                <span class="review-value" id="reviewFirstName">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Other Names</span>
                                <span class="review-value" id="reviewOtherNames">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Passport No.</span>
                                <span class="review-value font-mono" id="reviewPassport">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Nationality</span>
                                <span class="review-value" id="reviewNationality">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Passport Expiry</span>
                                <span class="review-value" id="reviewPassportExpiry">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-card">
                        <div class="review-card-title">
                            <i class="fas fa-plane" aria-hidden="true"></i> Travel Information
                        </div>
                        <div class="review-rows">
                            <div class="review-row">
                                <span class="review-label">Visa Category</span>
                                <span class="review-value" id="reviewVisaCategory">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Arrival Date</span>
                                <span class="review-value" id="reviewArrivalDate">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Address</span>
                                <span class="review-value" id="reviewAddress">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">City / State</span>
                                <span class="review-value" id="reviewCityState">—</span>
                            </div>
                            <div class="review-row">
                                <span class="review-label">Reason</span>
                                <span class="review-value" id="reviewApplicantNote">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-card review-card-full">
                        <div class="review-card-title">
                            <i class="fas fa-folder-open" aria-hidden="true"></i> Uploaded Documents
                        </div>
                        <div class="review-docs" id="reviewDocuments"></div>
                    </div>
                </div>

                <div class="notice review-notice">
                    <div class="notice-icon">
                        <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    </div>
                    <div class="notice-content">
                        <p>By submitting this application you confirm all information is truthful and accurate.
                           Misrepresentation may lead to rejection or legal consequences under Nigerian immigration law.</p>
                    </div>
                </div>

                <div class="wizard-panel-footer">
                    <button type="button" class="btn btn-outline wizard-prev" data-step="4">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Previous
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit Application
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<script>
(function () {
    'use strict';

    var TOTAL_STEPS  = 4;
    var currentStep  = 0;

    var panels = [
        document.getElementById('panel1'),
        document.getElementById('panel2'),
        document.getElementById('panel3'),
        document.getElementById('panel4'),
    ];

    var stepEls = [
        document.getElementById('wizStep1'),
        document.getElementById('wizStep2'),
        document.getElementById('wizStep3'),
        document.getElementById('wizStep4'),
    ];

    var progressFill = document.getElementById('wizardProgress');
    var progressBar  = progressFill ? progressFill.parentElement : null;

    // ── Wizard Navigation ─────────────────────────────────────
    function goToStep(index) {
        if (index < 0 || index >= TOTAL_STEPS) return;

        panels.forEach(function (p, i) {
            if (p) p.hidden = (i !== index);
        });

        stepEls.forEach(function (s, i) {
            if (!s) return;
            s.classList.toggle('active', i === index);
            s.classList.toggle('completed', i < index);
            s.setAttribute('aria-selected', i === index ? 'true' : 'false');
        });

        var pct = Math.round(((index + 1) / TOTAL_STEPS) * 100);
        if (progressFill) progressFill.style.width = pct + '%';
        if (progressBar)  progressBar.setAttribute('aria-valuenow', pct);

        currentStep = index;

        var wizard = document.querySelector('.app-wizard');
        if (wizard) wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function validatePanel(panelEl) {
        if (!panelEl) return true;

        // Validate all standard (non-file) inputs, selects, textareas inside this panel.
        // Note: panelEl is a <div>, so checkValidity() must be called on each field individually.
        var fields = panelEl.querySelectorAll('input:not([type="file"]), select, textarea');
        for (var i = 0; i < fields.length; i++) {
            if (!fields[i].checkValidity()) {
                fields[i].reportValidity();
                return false;
            }
        }

        // File inputs are visually hidden, so reportValidity() won't render a tooltip.
        // Mark the zone and show a toast instead.
        var fileInputs = panelEl.querySelectorAll('input[type="file"][required]');
        var missingFile = false;
        for (var j = 0; j < fileInputs.length; j++) {
            var inp = fileInputs[j];
            if (!inp.files || inp.files.length === 0) {
                var zoneEl = document.getElementById(inp.getAttribute('data-zone'));
                if (zoneEl) zoneEl.classList.add('upload-zone-invalid');
                missingFile = true;
            }
        }
        if (missingFile) {
            if (window.Dashboard) Dashboard.showToast('Please upload all required documents.', 'error');
            return false;
        }

        return true;
    }

    // Next buttons
    document.querySelectorAll('.wizard-next').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!validatePanel(panels[currentStep])) return;
            var next = currentStep + 1;
            if (next === 3) populateReview();
            goToStep(next);
        });
    });

    // Prev buttons
    document.querySelectorAll('.wizard-prev').forEach(function (btn) {
        btn.addEventListener('click', function () {
            goToStep(currentStep - 1);
        });
    });

    // ── Visa Category Subcategory Toggle ──────────────────────
    var visaSelect = document.getElementById('visa_category');
    var svvField   = document.getElementById('svvCategory');
    var trvField   = document.getElementById('trvCategory');

    function syncVisaSubcat() {
        var val    = visaSelect ? visaSelect.value : '';
        var isSVV  = val === 'SVV';
        var isTRV  = val === 'TRV';

        var svvSubcat = document.getElementById('svv_subcat');
        var trvSubcat = document.getElementById('trv_subcat');

        if (svvField) svvField.hidden = !isSVV;
        if (trvField) trvField.hidden = !isTRV;

        if (svvSubcat) {
            svvSubcat.disabled = !isSVV;
            if (!isSVV) svvSubcat.selectedIndex = 0;
        }
        if (trvSubcat) {
            trvSubcat.disabled = !isTRV;
            if (!isTRV) trvSubcat.selectedIndex = 0;
        }
    }

    if (visaSelect) {
        syncVisaSubcat();
        visaSelect.addEventListener('change', syncVisaSubcat);
    }

    // ── Reason Other Toggle ───────────────────────────────────
    var reasonSelect    = document.getElementById('applicant_note');
    var reasonOtherWrap = document.getElementById('reasonOtherWrapper');
    var reasonOtherInp  = document.getElementById('reason_other_text');

    function syncReasonOther() {
        var isOther = reasonSelect && reasonSelect.value === 'Other';
        if (reasonOtherWrap) reasonOtherWrap.hidden = !isOther;
        if (reasonOtherInp) {
            reasonOtherInp.required = isOther;
            reasonOtherInp.disabled = !isOther;
            if (!isOther) reasonOtherInp.value = '';
        }
    }

    if (reasonSelect) {
        syncReasonOther();
        reasonSelect.addEventListener('change', syncReasonOther);
    }

    // ── States / Cities ───────────────────────────────────────
    var stateEl    = document.getElementById('state');
    var cityEl     = document.getElementById('city');
    var oldState   = @json(old('state', ''));
    var oldCity    = @json(old('city', ''));
    var statesData = null;

    function loadStatesCities() {
        fetch('/assets/data/states_cities.json')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                statesData = data;
                Object.keys(data).sort().forEach(function (st) {
                    var opt = document.createElement('option');
                    opt.value = st;
                    opt.textContent = st;
                    if (st === oldState) opt.selected = true;
                    stateEl.appendChild(opt);
                });
                if (oldState) updateCityOptions(oldState);
            })
            .catch(function () { /* silent – state field remains empty */ });
    }

    function updateCityOptions(state) {
        cityEl.innerHTML = '<option value="">Select city</option>';
        cityEl.disabled  = true;
        if (!state || !statesData || !statesData[state]) return;
        statesData[state].forEach(function (city) {
            var opt = document.createElement('option');
            opt.value = city;
            opt.textContent = city;
            if (city === oldCity) opt.selected = true;
            cityEl.appendChild(opt);
        });
        cityEl.disabled = false;
    }

    if (stateEl) {
        loadStatesCities();
        stateEl.addEventListener('change', function () {
            updateCityOptions(stateEl.value);
        });
    }

    // ── File Upload Preview ───────────────────────────────────
    function formatBytes(bytes) {
        bytes = parseInt(bytes, 10);
        if (isNaN(bytes) || bytes === 0) return '';
        if (bytes < 1024)    return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function showFilePreview(file, zoneEl, previewEl) {
        while (previewEl.firstChild) previewEl.removeChild(previewEl.firstChild);
        previewEl.hidden = false;

        var isImage = file.type.startsWith('image/');

        var inner = document.createElement('div');
        inner.className = 'upload-preview-inner';

        // Thumbnail or PDF icon
        var thumbWrap = document.createElement('div');
        thumbWrap.className = 'upload-thumb';

        if (isImage) {
            var img = document.createElement('img');
            img.alt = file.name;
            img.src = URL.createObjectURL(file);
            img.onload = function () { URL.revokeObjectURL(img.src); };
            thumbWrap.appendChild(img);
        } else {
            var pdfIcon = document.createElement('i');
            pdfIcon.className = 'fas fa-file-pdf upload-pdf-icon';
            pdfIcon.setAttribute('aria-hidden', 'true');
            thumbWrap.appendChild(pdfIcon);
        }

        // File info
        var infoEl = document.createElement('div');
        infoEl.className = 'upload-file-info';

        var nameEl = document.createElement('span');
        nameEl.className   = 'upload-file-name';
        nameEl.textContent = file.name;

        var sizeEl = document.createElement('span');
        sizeEl.className   = 'upload-file-size';
        sizeEl.textContent = formatBytes(file.size);

        infoEl.appendChild(nameEl);
        infoEl.appendChild(sizeEl);

        // Clear button
        var clearBtn = document.createElement('button');
        clearBtn.type      = 'button';
        clearBtn.className = 'upload-clear-btn';
        clearBtn.setAttribute('aria-label', 'Remove file');
        var xi = document.createElement('i');
        xi.className = 'fas fa-times';
        xi.setAttribute('aria-hidden', 'true');
        clearBtn.appendChild(xi);

        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var inputEl = document.getElementById(zoneEl.getAttribute('for'));
            if (inputEl) {
                inputEl.value = '';
                inputEl.dispatchEvent(new Event('change'));
            }
        });

        inner.appendChild(thumbWrap);
        inner.appendChild(infoEl);
        inner.appendChild(clearBtn);
        previewEl.appendChild(inner);

        zoneEl.classList.add('has-file');
        zoneEl.classList.remove('upload-zone-invalid');
    }

    function clearFilePreview(zoneEl, previewEl) {
        while (previewEl.firstChild) previewEl.removeChild(previewEl.firstChild);
        previewEl.hidden = true;
        zoneEl.classList.remove('has-file');
    }

    var ACCEPTED_TYPES = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

    document.querySelectorAll('.upload-input').forEach(function (input) {
        var zoneId    = input.getAttribute('data-zone');
        var previewId = input.getAttribute('data-preview');
        var zoneEl    = document.getElementById(zoneId);
        var previewEl = document.getElementById(previewId);
        if (!zoneEl || !previewEl) return;

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                showFilePreview(this.files[0], zoneEl, previewEl);
            } else {
                clearFilePreview(zoneEl, previewEl);
            }
        });

        // Drag-and-drop
        zoneEl.addEventListener('dragover', function (e) {
            e.preventDefault();
            zoneEl.classList.add('drag-over');
        });

        zoneEl.addEventListener('dragleave', function () {
            zoneEl.classList.remove('drag-over');
        });

        zoneEl.addEventListener('drop', function (e) {
            e.preventDefault();
            zoneEl.classList.remove('drag-over');
            var files = e.dataTransfer ? e.dataTransfer.files : null;
            if (!files || !files[0]) return;

            if (ACCEPTED_TYPES.indexOf(files[0].type) === -1) {
                if (window.Dashboard) Dashboard.showToast('Please upload a PDF, JPG, or PNG file.', 'error');
                return;
            }

            try {
                var dt = new DataTransfer();
                dt.items.add(files[0]);
                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            } catch (err) { /* DataTransfer unsupported in some browsers */ }
        });
    });

    // ── Review Population ─────────────────────────────────────
    function getVal(id) {
        var el = document.getElementById(id);
        return el ? (el.value.trim() || '—') : '—';
    }

    function getSelectedText(selectEl) {
        if (!selectEl) return '—';
        var opt = selectEl.options[selectEl.selectedIndex];
        return (opt && opt.value) ? opt.textContent.trim() : '—';
    }

    function setReviewText(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val || '—';
    }

    function populateReview() {
        setReviewText('reviewSurname',       getVal('surname'));
        setReviewText('reviewFirstName',     getVal('first_name'));
        setReviewText('reviewOtherNames',    getVal('other_names'));
        setReviewText('reviewPassport',      getVal('passport_number'));
        setReviewText('reviewNationality',   getSelectedText(document.getElementById('nationality')));
        setReviewText('reviewPassportExpiry', getVal('passport_expiry'));

        var visaCat = getSelectedText(document.getElementById('visa_category'));
        var subCat  = '';
        if (visaSelect) {
            if (visaSelect.value === 'SVV') subCat = getSelectedText(document.getElementById('svv_subcat'));
            if (visaSelect.value === 'TRV') subCat = getSelectedText(document.getElementById('trv_subcat'));
        }
        var visaFull = visaCat !== '—' && subCat && subCat !== '—' ? visaCat + ' – ' + subCat : visaCat;
        setReviewText('reviewVisaCategory', visaFull);

        setReviewText('reviewArrivalDate',   getVal('arrival_date'));
        setReviewText('reviewAddress',       getVal('address'));

        var city  = getVal('city');
        var state = getVal('state');
        var cityState = [city, state].filter(function (v) { return v && v !== '—'; }).join(' / ');
        setReviewText('reviewCityState', cityState || '—');

        setReviewText('reviewApplicantNote', getSelectedText(document.getElementById('applicant_note')));

        renderReviewDocs();
    }

    function renderReviewDocs() {
        var container = document.getElementById('reviewDocuments');
        if (!container) return;
        while (container.firstChild) container.removeChild(container.firstChild);

        var fields = [
            { id: 'passport_data_page', label: 'Passport Data Page' },
            { id: 'entry_visa',         label: 'Entry Visa'          },
            { id: 'entry_stamp',        label: 'Entry Stamp'         },
            { id: 'return_ticket',      label: 'Return Ticket'       },
            { id: 'passport_photo',        label: 'Passport Photo'      },
            { id: 'flight_cancellation', label: 'Proof of Flight Cancellation' },
        ];

        fields.forEach(function (f) {
            var input   = document.getElementById(f.id);
            var hasFile = input && input.files && input.files.length > 0;
            var file    = hasFile ? input.files[0] : null;

            var row = document.createElement('div');
            row.className = 'review-doc-row';

            var icon = document.createElement('i');
            icon.className = hasFile
                ? 'fas fa-check-circle review-doc-icon review-doc-ok'
                : 'fas fa-times-circle review-doc-icon review-doc-missing';
            icon.setAttribute('aria-hidden', 'true');

            var label = document.createElement('span');
            label.className   = 'review-doc-label';
            label.textContent = f.label;

            var status = document.createElement('span');
            status.className   = 'review-doc-status';
            status.textContent = file ? file.name : 'Not uploaded';

            row.appendChild(icon);
            row.appendChild(label);
            row.appendChild(status);
            container.appendChild(row);
        });
    }

    // ── Submit guard ──────────────────────────────────────────
    var form = document.getElementById('registrationForm');
    if (form) {
        form.addEventListener('submit', function () {
            var btn = document.getElementById('submitBtn');
            if (btn) {
                btn.disabled = true;
                while (btn.firstChild) btn.removeChild(btn.firstChild);
                var spinner = document.createElement('i');
                spinner.className = 'fas fa-spinner fa-spin';
                spinner.setAttribute('aria-hidden', 'true');
                btn.appendChild(spinner);
                btn.appendChild(document.createTextNode(' Submitting\u2026'));
            }
        });
    }

    // ── Init ──────────────────────────────────────────────────
    goToStep(0);

}());
</script>

@include('partials.footer')
