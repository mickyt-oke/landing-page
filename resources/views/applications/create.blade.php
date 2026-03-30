@include('partials.header')

<main class="dashboard-content px-3 py-4">
    <div class="container-fluid">
            @if(session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                                @endif
                                
        @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                                    <ul class="mb-0">
                                                        @foreach($errors->all() as $error)
                                                                                <li>{{ $error }}</li>
                                                                                                    @endforeach
                                                                                                                    </ul>
                                                                                                                                </div>
                                                                                                                                        @endif
                                                                                                                                        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
                    <div>
                                    <h2 class="mb-1">New Registration</h2>
                                                    <p class="text-muted mb-0">Complete your profile and upload your documents to start your application.</p>
                                                                </div>
                                                                        </div>
                                                                        
        <div class="card shadow-sm">
                    <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                                                        <div class="step-progress">Progress: <strong class="progress-percentage">0%</strong> completed</div>
                                                                            <div class="step-indicator d-flex gap-2" data-progress="0">
                                                                                                    <div class="step active" id="step1" data-step="Biodata">
                                                                                                                                <div class="step-number">1</div>
                                                                                                                                                            <div class="step-text">Biodata</div>
                                                                                                                                                                                    </div>
                                                                                                                                                                                                            <div class="step" id="step2" data-step="Travel Info">
                                                                                                                                                                                                                                        <div class="step-number">2</div>
                                                                                                                                                                                                                                                                    <div class="step-text">Travel Info</div>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                    <div class="step" id="step3" data-step="Documents">
                                                                                                                                                                                                                                                                                                                                                <div class="step-number">3</div>
                                                                                                                                                                                                                                                                                                                                                                            <div class="step-text">Documents</div>
                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="step" id="step4" data-step="Review">
                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="step-number">4</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="step-text">Review</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                <form id="registrationForm" method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data" novalidate>
                                    @csrf
                                    
                    <div id="step1Form" class="step-form" style="display: block;">
                                            <div class="row gy-3">
                                                                        <div class="col-md-6">
                                                                                                        <label for="surname" class="form-label">Surname</label>
                                                                                                                                        <input id="surname" name="surname" type="text" class="form-control @error('surname') is-invalid @enderror" placeholder="Enter surname" required value="{{ old('surname', $prefill['regSurname'] ?? '') }}">
                                                                                                                                                                        @error('surname')
                                                                                                                                                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                            @enderror
                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                        
                            <div class="col-md-6">
                                                            <label for="first_name" class="form-label">First Name</label>
                                                                                            <input id="first_name" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="Enter first name" required value="{{ old('first_name', $prefill['regFirstName'] ?? '') }}">
                                                                                                                            @error('first_name')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="other_names" class="form-label">Other Names</label>
                                                                                            <input id="other_names" name="other_names" type="text" class="form-control @error('other_names') is-invalid @enderror" placeholder="Enter other names (optional)" value="{{ old('other_names', $prefill['regOtherNames'] ?? '') }}">
                                                                                                                            @error('other_names')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="passport_number" class="form-label">Passport Number</label>
                                                                                            <input id="passport_number" name="passport_number" type="text" class="form-control @error('passport_number') is-invalid @enderror" placeholder="Enter passport number" required value="{{ old('passport_number', $prefill['regPassport'] ?? '') }}">
                                                                                                                            @error('passport_number')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="nationality" class="form-label">Nationality</label>
                                                                                            <select id="nationality" name="nationality" class="form-select @error('nationality') is-invalid @enderror" required>
                                                                                                                                <option value="">Select nationality</option>
                                                                                                                                                                    @foreach($nationalities as $nat)
                                                                                                                                                                                                            <option value="{{ $nat }}" {{ old('nationality', $prefill['regNationality'] ?? '') === $nat ? 'selected' : '' }}>{{ $nat }}</option>
                                                                                                                                                                                                                                                @endforeach
                                                                                                                                                                                                                                                                                </select>
                                                                                                                                                                                                                                                                                                                @error('nationality')
                                                                                                                                                                                                                                                                                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                                                                                                                                                                    @enderror
                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                
                            <div class="col-md-6">
                                                            <label for="passport_expiry" class="form-label">Passport Expiry Date</label>
                                                                                            <input id="passport_expiry" name="passport_expiry" type="date" class="form-control" value="{{ old('passport_expiry') }}">
                                                                                                                        </div>
                                                                                                                                                </div>
                                                                                                                                                
                        <div class="mt-4 d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary" data-next-step="1">Next <i class="fas fa-arrow-right ms-2"></i></button>
                                                                            </div>
                                                                                                </div>
                                                                                                
                    <div id="step2Form" class="step-form" style="display: none;">
                                            <div class="row gy-3">
                                                                        <div class="col-md-6">
                                                                                                        <label for="visa_category" class="form-label">Visa Category</label>
                                                                                                                                        <select id="visa_category" name="visa_category" class="form-select @error('visa_category') is-invalid @enderror" required>
                                                                                                                                                                            <option value="">Select Visa Category</option>
                                                                                                                                                                                                                <option value="SVV" {{ old('visa_category') === 'SVV' ? 'selected' : '' }}>Short Visit Visa (SVV)</option>
                                                                                                                                                                                                                                                    <option value="TRV" {{ old('visa_category') === 'TRV' ? 'selected' : '' }}>Temporary Residence Visa (TRV)</option>
                                                                                                                                                                                                                                                                                    </select>
                                                                                                                                                                                                                                                                                                                    @error('visa_category')
                                                                                                                                                                                                                                                                                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                                                                                                                                                                        @enderror
                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                    
                            <div class="col-md-6" id="svvCategory" style="display: none;">
                                                            <label for="svvSubCat" class="form-label">SVV Sub-category</label>
                                                                                            <select id="svvSubCat" name="svv_subcategory" class="form-select" aria-label="SVV Sub-category">
                                                                                                                                <option value="">Select type</option>
                                                                                                                                                                    <option value="F4A" {{ old('svv_subcategory') === 'F4A' ? 'selected' : '' }}>F4A</option>
                                                                                                                                                                                                        <option value="F4B" {{ old('svv_subcategory') === 'F4B' ? 'selected' : '' }}>F4B</option>
                                                                                                                                                                                                                                            <option value="F5A" {{ old('svv_subcategory') === 'F5A' ? 'selected' : '' }}>F5A</option>
                                                                                                                                                                                                                                                                                <option value="F6A" {{ old('svv_subcategory') === 'F6A' ? 'selected' : '' }}>F6A</option>
                                                                                                                                                                                                                                                                                                                </select>
                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                            
                            <div class="col-md-6" id="trvCategory" style="display: none;">
                                                            <label for="trvSubCat" class="form-label">TRV Sub-category</label>
                                                                                            <select id="trvSubCat" name="trv_subcategory" class="form-select" aria-label="TRV Sub-category">
                                                                                                                                <option value="">Select type</option>
                                                                                                                                                                    <option value="R2A" {{ old('trv_subcategory') === 'R2A' ? 'selected' : '' }}>R2A</option>
                                                                                                                                                                                                        <option value="R6A" {{ old('trv_subcategory') === 'R6A' ? 'selected' : '' }}>R6A</option>
                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                    
                            <div class="col-md-4">
                                                            <label for="arrival_date" class="form-label">Arrival Date</label>
                                                                                            <input id="arrival_date" name="arrival_date" type="date" class="form-control @error('arrival_date') is-invalid @enderror" required value="{{ old('arrival_date') }}">
                                                                                                                            @error('arrival_date')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-8">
                                                            <label for="address" class="form-label">Address in Nigeria</label>
                                                                                            <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror" placeholder="Street address" required value="{{ old('address') }}">
                                                                                                                            @error('address')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="state" class="form-label">State</label>
                                                                                            <select id="state" name="state" class="form-select @error('state') is-invalid @enderror" required>
                                                                                                                                <option value="">Select state</option>
                                                                                                                                                                </select>
                                                                                                                                                                                                @error('state')
                                                                                                                                                                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                                                    @enderror
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                
                            <div class="col-md-6">
                                                            <label for="city" class="form-label">City</label>
                                                                                            <select id="city" name="city" class="form-select @error('city') is-invalid @enderror" required disabled>
                                                                                                                                <option value="">Select city</option>
                                                                                                                                                                </select>
                                                                                                                                                                                                @error('city')
                                                                                                                                                                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                                                    @enderror
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                
                            <div class="col-12">
                                                            <label for="applicant_note" class="form-label">Reason for Request</label>
                                                                                            <select id="applicant_note" name="applicant_note" class="form-select" aria-describedby="reasonHelp">
                                                                                                                                <option value="">Select reason</option>
                                                                                                                                                                    <option value="Flight Cancellation" {{ old('applicant_note') === 'Flight Cancellation' ? 'selected' : '' }}>Flight Cancellation</option>
                                                                                                                                                                                                        <option value="Delayed Flight" {{ old('applicant_note') === 'Delayed Flight' ? 'selected' : '' }}>Delayed Flight</option>
                                                                                                                                                                                                                                            <option value="Other" {{ old('applicant_note') === 'Other' ? 'selected' : '' }}>Other</option>
                                                                                                                                                                                                                                                                            </select>
                                                                                                                                                                                                                                                                                                            <div id="reasonHelp" class="form-text">Optional note sent with your application.</div>
                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                        
                            <div class="col-12" id="reasonOtherWrapper" style="display: none;">
                                                            <label for="reason_other_text" class="form-label">Please specify reason</label>
                                                                                            <textarea id="reason_other_text" name="reason_other_text" class="form-control" rows="3">{{ old('reason_other_text') }}</textarea>
                                                                                                                        </div>
                                                                                                                                                </div>
                                                                                                                                                
                        <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-prev-step="2">Previous</button>
                                                                                <button type="button" class="btn btn-primary" data-next-step="2">Next <i class="fas fa-arrow-right ms-2"></i></button>
                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                            
                    <div id="step3Form" class="step-form" style="display: none;">
                                            <div class="row gy-3">
                                                                        <div class="col-md-6">
                                                                                                        <label for="passport_data_page" class="form-label">Passport Data Page <span class="text-danger">*</span></label>
                                                                                                                                        <input id="passport_data_page" name="passport_data_page" type="file" class="form-control @error('passport_data_page') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                                                                                                                                                        @error('passport_data_page')
                                                                                                                                                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                                                            @enderror
                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                        
                            <div class="col-md-6">
                                                            <label for="entry_visa" class="form-label">Entry Visa <span class="text-danger">*</span></label>
                                                                                            <input id="entry_visa" name="entry_visa" type="file" class="form-control @error('entry_visa') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                                                                                                            @error('entry_visa')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="entry_stamp" class="form-label">Entry Stamp <span class="text-danger">*</span></label>
                                                                                            <input id="entry_stamp" name="entry_stamp" type="file" class="form-control @error('entry_stamp') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                                                                                                            @error('entry_stamp')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            
                            <div class="col-md-6">
                                                            <label for="return_ticket" class="form-label">Return Ticket <span class="text-danger">*</span></label>
                                                                                            <input id="return_ticket" name="return_ticket" type="file" class="form-control @error('return_ticket') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                                                                                                            @error('return_ticket')
                                                                                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                                                                                                @enderror
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                    
                        <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-prev-step="3">Previous</button>
                                                                                <button type="button" class="btn btn-primary" data-next-step="3">Next <i class="fas fa-arrow-right ms-2"></i></button>
                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                            
                    <div id="step4Form" class="step-form" style="display: none;">
                                            <div class="card border-0 shadow-sm">
                                                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                                                                        <div>
                                                                                                                                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Review Your Information</h5>
                                                                                                                                                                                <small class="text-white-75">Confirm the details below before submitting.</small>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                <span class="badge bg-light text-primary">Step 4 of 4</span>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                        <div class="card-body">
                                                                                                                                                                                                                                                                                                                                        <div class="row gy-4">
                                                                                                                                                                                                                                                                                                                                                                            <div class="col-lg-6">
                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="card h-100 border-secondary">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="card-header bg-light">Personal Information</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-body">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-2"><strong>Surname:</strong> <span id="reviewSurname"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-2"><strong>First Name:</strong> <span id="reviewFirstName"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-2"><strong>Other Names:</strong> <span id="reviewOtherNames"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-2"><strong>Passport:</strong> <span id="reviewPassport"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-2"><strong>Nationality:</strong> <span id="reviewNationality"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <p class="mb-0"><strong>Passport Expiry:</strong> <span id="reviewPassportExpiry"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                                    <div class="col-lg-6">
                                                                            <div class="card h-100 border-secondary">
                                                                                                                        <div class="card-header bg-light">Travel Information</div>
                                                                                                                                                                    <div class="card-body">
                                                                                                                                                                                                                    <p class="mb-2"><strong>Visa Category:</strong> <span id="reviewVisaCategory"></span></p>
                                                                                                                                                                                                                                                                    <p class="mb-2"><strong>Arrival Date:</strong> <span id="reviewArrivalDate"></span></p>
                                                                                                                                                                                                                                                                                                                    <p class="mb-2"><strong>Address:</strong> <span id="reviewAddress"></span></p>
                                                                                                                                                                                                                                                                                                                                                                    <p class="mb-2"><strong>City / State:</strong> <span id="reviewCityState"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                    <p class="mb-0"><strong>Reason:</strong> <span id="reviewApplicantNote"></span></p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                                <div class="card mt-4 border-secondary">
                                                                    <div class="card-header bg-light">Documents Uploaded</div>
                                                                                                        <div class="card-body">
                                                                                                                                                <ul class="list-unstyled mb-0">
                                                                                                                                                                                            <li><i class="fas fa-check-circle text-success me-2"></i>Passport Data Page</li>
                                                                                                                                                                                                                                        <li><i class="fas fa-check-circle text-success me-2"></i>Entry Visa</li>
                                                                                                                                                                                                                                                                                    <li><i class="fas fa-check-circle text-success me-2"></i>Entry Stamp</li>
                                                                                                                                                                                                                                                                                                                                <li><i class="fas fa-check-circle text-success me-2"></i>Return Ticket</li>
                                                                                                                                                                                                                                                                                                                                                                        </ul>
                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                        <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-prev-step="4">Edit</button>
                                                                                <button type="submit" class="btn btn-success">Complete Application</button>
                                                                                                        </div>
                                                                                                                            </div>
                                                                                                                                            </form>
                                                                                                                                                        </div>
                                                                                                                                                                </div>
                                                                                                                                                                    </div>
                                                                                                                                                                    </main>
                                                                                                                                                                    
<script>
    document.addEventListener('DOMContentLoaded', function () {
            const stepForms = document.querySelectorAll('.step-form');
                    const stepIndicatorItems = document.querySelectorAll('.step-indicator .step');
                            const progressPercentage = document.querySelector('.progress-percentage');
                                    const visaCategory = document.getElementById('visa_category');
                                            const svvCategory = document.getElementById('svvCategory');
                                                    const trvCategory = document.getElementById('trvCategory');
                                                            const reasonSelect = document.getElementById('applicant_note');
                                                                    const reasonOtherWrapper = document.getElementById('reasonOtherWrapper');
                                                                            const reasonOtherText = document.getElementById('reason_other_text');
                                                                                    const stateEl = document.getElementById('state');
                                                                                            const cityEl = document.getElementById('city');
                                                                                                    const oldState = '{{ old('state') }}';
                                                                                                            const oldCity = '{{ old('city') }}';
                                                                                                            
        const updateStep = (index) => {
                    stepForms.forEach((form, idx) => {
                                    form.style.display = idx === index ? 'block' : 'none';
                                                });
                                                            stepIndicatorItems.forEach((item, idx) => {
                                                                            item.classList.toggle('active', idx <= index);
                                                                                        });
                                                                                                    const progress = Math.round(((index + 1) / stepForms.length) * 100);
                                                                                                                progressPercentage.textContent = `${progress}%`;
                                                                                                                        };
                                                                                                                        
        const showVisaSubcategory = () => {
                    const value = visaCategory.value;
                                svvCategory.style.display = value === 'SVV' ? 'block' : 'none';
                                            trvCategory.style.display = value === 'TRV' ? 'block' : 'none';
                                                    };
                                                    
        const showReasonExtras = () => {
                    const value = reasonSelect.value;
                                if (value === 'Other') {
                                                reasonOtherWrapper.style.display = 'block';
                                                                reasonOtherText.required = true;
                                                                            } else {
                                                                                            reasonOtherWrapper.style.display = 'none';
                                                                                                            reasonOtherText.required = false;
                                                                                                                            reasonOtherText.value = '';
                                                                                                                                        }
                                                                                                                                                };
                                                                                                                                                
        const loadStatesCities = () => {
                    fetch('/assets/data/states_cities.json')
                                    .then(response => response.json())
                                                    .then(data => {
                                                                        Object.keys(data).sort().forEach((state) => {
                                                                                                const option = document.createElement('option');
                                                                                                                        option.value = state;
                                                                                                                                                option.textContent = state;
                                                                                                                                                                        if (state === oldState) {
                                                                                                                                                                                                    option.selected = true;
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                    stateEl.appendChild(option);
                                                                                                                                                                                                                                                                        });
                                                                                                                                                                                                                                                                        
                    if (oldState) {
                                            updateCityOptions(oldState, data);
                                                                }
                                                                                })
                                                                                                .catch(() => {
                                                                                                                    console.warn('Could not load states/cities JSON.');
                                                                                                                                    });
                                                                                                                                            };
                                                                                                                                            
        const updateCityOptions = (state, data) => {
                    cityEl.innerHTML = '<option value="">Select city</option>';
                                cityEl.disabled = true;
                                
            if (!state || !data || !data[state]) {
                            return;
                                        }
                                        
            data[state].forEach(city => {
                            const option = document.createElement('option');
                                            option.value = city;
                                                            option.textContent = city;
                                                                            if (city === oldCity) {
                                                                                                option.selected = true;
                                                                                                                }
                                                                                                                                cityEl.appendChild(option);
                                                                                                                                            });
                                                                                                                                            
            cityEl.disabled = false;
                    };
                    
        const populateReview = () => {
                    document.getElementById('reviewSurname').textContent = document.getElementById('surname').value ; '';
                                document.getElementById('reviewFirstName').textContent = document.getElementById('first_name').value ; '';
                                            document.getElementById('reviewOtherNames').textContent = document.getElementById('other_names').value ; '';
                                                        document.getElementById('reviewPassport').textContent = document.getElementById('passport_number').value ; '';
                                                                    document.getElementById('reviewNationality').textContent = document.getElementById('nationality').selectedOptions[0]?.textContent ; '';
                                                                                document.getElementById('reviewPassportExpiry').textContent = document.getElementById('passport_expiry').value ; '';
                                                                                            document.getElementById('reviewVisaCategory').textContent = document.getElementById('visa_category').selectedOptions[0]?.textContent ; '';
                                                                                                        document.getElementById('reviewArrivalDate').textContent = document.getElementById('arrival_date').value ; '';
                                                                                                                    document.getElementById('reviewAddress').textContent = document.getElementById('address').value ; '';
                                                                                                                                document.getElementById('reviewCityState').textContent = [document.getElementById('city').value, document.getElementById('state').value].filter(Boolean).join(' / ') ; '';
                                                                                                                                            document.getElementById('reviewApplicantNote').textContent = document.getElementById('applicant_note').value ; '';
                                                                                                                                                    };
                                                                                                                                                    
        document.querySelectorAll('[data-next-step]').forEach(button => {
                    button.addEventListener('click', () => {
                                    const currentStep = Number(button.getAttribute('data-next-step'));
                                                    const currentForm = document.querySelector(`#step${currentStep}Form`);
                                                    
                if (currentForm ; !currentForm.checkValidity()) {
                                    currentForm.reportValidity();
                                                        return;
                                                                        }
                                                                        
                const nextIndex = currentStep;
                                if (nextIndex === 3) {
                                                    populateReview();
                                                                    }
                                                                                    updateStep(nextIndex);
                                                                                                });
                                                                                                        });
                                                                                                        
        document.querySelectorAll('[data-prev-step]').forEach(button => {
                    button.addEventListener('click', () => {
                                    const prevStep = Number(button.getAttribute('data-prev-step')) - 1;
                                                    updateStep(prevStep);
                                                                });
                                                                        });
                                                                        
        if (visaCategory) {
                    showVisaSubcategory();
                                visaCategory.addEventListener('change', showVisaSubcategory);
                                        }
                                        
        if (reasonSelect) {
                    showReasonExtras();
                                reasonSelect.addEventListener('change', showReasonExtras);
                                        }
                                        
        if (stateEl) {
                    loadStatesCities();
                                stateEl.addEventListener('change', () => {
                                                fetch('/assets/data/states_cities.json')
                                                                    .then(response => response.json())
                                                                                        .then(data => updateCityOptions(stateEl.value, data));
                                                                                                    });
                                                                                                            }
                                                                                                            
        updateStep(0);
            });
            </script>
            
@include('partials.footer')
