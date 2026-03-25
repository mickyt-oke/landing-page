<?php
/**
 * User Dashboard - Migrants Overstay Portal
 * Nigeria Immigration Service
 */


// Load nationalities from JSON
$nationalities = json_decode(file_get_contents('assets/data/nationalities.json'), true);

// server-side form processing
$errors = [];
$old = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize and preserve old values
    $fields = [
        'regSurname','regFirstName','regOtherNames','regPassport','regNationality',
        'regPassportExpiry','regEmail','regPassword1','regPassword2',
        'visaCategory','svvSubCat','trvSubCat','arrivalDate','addressNigeria',
        'city','state','reasonRequest'
    ];
    foreach ($fields as $f) {
        $old[$f] = trim($_POST[$f] ?? '');
    }

    // required fields
    foreach (['regSurname','regFirstName','regPassport','regNationality','regPassportExpiry','regEmail','regPassword1','regPassword2','visaCategory','arrivalDate','addressNigeria','city','state','reasonRequest'] as $f) {
        if (empty($old[$f])) {
            $errors[] = str_replace('reg', '', ucfirst($f)) . ' is required';
        }
    }

    // email validation
    if (!empty($old['regEmail']) && !filter_var($old['regEmail'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }

    // password rules
    if ($old['regPassword1'] !== $old['regPassword2']) {
        $errors[] = 'Passwords do not match';
    }
    if (strlen($old['regPassword1']) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }

    // date rules
    if (!empty($old['regPassportExpiry']) && strtotime($old['regPassportExpiry']) < time()) {
        $errors[] = 'Passport expiry date must be in the future';
    }
    if (!empty($old['arrivalDate']) && strtotime($old['arrivalDate']) > time()) {
        $errors[] = 'Arrival date cannot be in the future';
    }

    // file validation
    $allowedTypes = ['image/jpeg','image/png','application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    $fileFields = ['passportDataFile','visaFile','stampFile','ticketFile'];
    foreach ($fileFields as $key) {
        if (empty($_FILES[$key]['name'])) {
            $errors[] = ucfirst($key) . ' is required';
        } elseif ($_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
            $errors[] = ucfirst($key) . ' upload error';
        } else {
            if (!in_array($_FILES[$key]['type'], $allowedTypes)) {
                $errors[] = ucfirst($key) . ' must be a JPEG, PNG or PDF';
            }
            if ($_FILES[$key]['size'] > $maxSize) {
                $errors[] = ucfirst($key) . ' must be smaller than 5 MB';
            }
        }
    }

    if (empty($errors)) {
        $ackRef = sprintf("%010d", mt_rand(1000000000, 9999999999));
        $submittedDate = date('d M Y g:i A');
        $applicantName = trim(($old['regSurname'] ?? '') . ' ' . ($old['regFirstName'] ?? '') . ' ' . ($old['regOtherNames'] ?? ''));
        $passportNo = $old['regPassport'] ?? '';
        $nationality = $old['regNationality'] ?? '';
        $visaCategory = $old['visaCategory'] ?? ($old['svvSubCat'] ?? ($old['trvSubCat'] ?? ''));
        $arrivalDate = $old['arrivalDate'] ?? '';
        // TODO: persist data to database with $ackRef, move uploaded files
        // $pdo->prepare("INSERT INTO applications ...");
        // move_uploaded_file($_FILES['passportDataFile']['tmp_name'], $uploadPath);
        $successMessage = ''; // Override for full ack page
    }
}

// Placeholder user data - replace with actual database query
$currentUser = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'role' => 'Applicant',
    'initials' => 'JD'
];

// Placeholder statistics - replace with actual database queries
$stats = [
    'total_applications' => 3,
    'pending' => 1,
    'approved' => 1,
    'rejected' => 1
];

// Placeholder recent applications
$recentApplications = [
    [
        'id' => 'APP001',
        'type' => 'Overstay Clearance',
        'submitted_date' => '2024-03-15',
        'status' => 'pending',
        'status_label' => 'Pending Review'
    ],
    [
        'id' => 'APP002',
        'type' => 'Overstay Clearance',
        'submitted_date' => '2024-03-10',
        'status' => 'approved',
        'status_label' => 'Approved'
    ],
    [
        'id' => 'APP003',
        'type' => 'Overstay Clearance',
        'submitted_date' => '2024-03-05',
        'status' => 'rejected',
        'status_label' => 'Rejected'
    ]
];
?>

@include ('partials.header')

<?php if (!empty($errors)): ?>
    <div class="form-messages error" role="alert">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php elseif (isset($ackRef)): ?>
    <style>
        @media print {
            .no-print { display: none !important; }
            .ack-section { max-width: 8.5in; margin: 0 auto; padding: 1rem; font-size: 12pt; }
            body, html { margin: 0; padding: 0; }
        }
        .nis-header { background: linear-gradient(135deg, #003087, #0056b3); color: white; padding: 2rem 1rem; text-align: center; }
        .ref-badge { background: #28a745; color: white; padding: 1rem 2rem; border-radius: 30px; font-weight: bold; font-size: 1.5em; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .ack-details { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 1rem 0; }
        .ack-details table td:first-child { font-weight: bold; width: 40%; padding-right: 1rem; }
        .next-steps { background: linear-gradient(to right, #f8f9fa, #e9ecef); border-left: 5px solid #28a745; padding: 1.5rem; border-radius: 8px; }
    </style>
    <script>
        window.onload = function() { if (window.matchMedia && window.matchMedia('(print)').matches) return; };
        window.onafterprint = function() { location.reload(); };
        document.querySelectorAll('[id^=step]').forEach(el => el.style.display = 'none');
    </script>
    <div class="ack-section p-4">
        <div class="no-print text-center mb-4">
            <button onclick="window.print()" style="background: #28a745; color: white; border: none; padding: 1rem 2.5rem; font-size: 1.2em; border-radius: 30px; cursor: pointer; box-shadow: 0 4px 12px rgba(40,167,69,0.3);">
                <i class="fas fa-print"></i> Print This Acknowledgement
            </button>
        </div>
        <div class="nis-header mb-4">
            <img src="assets/images/nis-logo.png" alt="NIS Logo" style="height: 80px; margin-bottom: 1rem;">
            <h1 style="font-size: 2.2em; margin: 0;">Nigeria Immigration Service</h1>
            <p style="font-size: 1.3em; opacity: 0.9;">Foreigners Registration &amp; Overstay Clearance Portal</p>
        </div>
        <div class="ack-details">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h1 style="color: #003087; font-size: 2.8em; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">APPLICATION ACKNOWLEDGEMENT</h1>
                <div class="ref-badge mb-3" style="font-size: 1.6em; padding: 1.2rem 2.5rem;">REFERENCE NUMBER: <?php echo $ackRef; ?></div>
                <p style="font-size: 1.4em; color: #333; margin: 0;">Submission Date: <strong><?php echo $submittedDate; ?></strong></p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 2.5rem;">
                <div>
                    <h3 style="color: #003087; border-bottom: 3px solid #003087; padding-bottom: 0.8rem; margin-bottom: 1.5rem;">Applicant Information</h3>
                    <table class="ack-details">
                        <tr style="padding: 0.5rem 0;"><td>Full Name:</td><td><?php echo htmlspecialchars($applicantName); ?></td></tr>
                        <tr style="padding: 0.5rem 0;"><td>Passport Number:</td><td><?php echo htmlspecialchars($passportNo); ?></td></tr>
                        <tr style="padding: 0.5rem 0;"><td>Nationality:</td><td><?php echo htmlspecialchars($nationality); ?></td></tr>
                        <tr style="padding: 0.5rem 0;"><td>Visa Category:</td><td><?php echo htmlspecialchars($visaCategory); ?></td></tr>
                    </table>
                </div>
                <div>
                    <h3 style="color: #003087; border-bottom: 3px solid #003087; padding-bottom: 0.8rem; margin-bottom: 1.5rem;">Application Details</h3>
                    <table class="ack-details">
                        <tr style="padding: 0.5rem 0;"><td>Arrival Date:</td><td><?php echo htmlspecialchars($arrivalDate); ?></td></tr>
                        <tr style="padding: 0.5rem 0;"><td>Current Status:</td><td style="color: #28a745; font-weight: bold; font-size: 1.1em;">Submitted - Pending Review</td></tr>
                        <tr style="padding: 0.5rem 0;"><td>Application Type:</td><td>Middle East Temporary Program</td></tr>
                    </table>
                </div>
            </div>
            <div class="next-steps">
                <h4 style="margin-top: 0; color: #28a745;">📋 Next Steps</h4>
                <ul style="font-size: 1.1em; line-height: 1.7;">
                    <li><strong>✅</strong> Your application has been successfully received and entered into our system.</li>
                    <li><strong>🔍</strong> Login to your dashboard to track real-time status using this reference number.</li>
                    <li><strong>⏱️</strong> Expected processing time: 5-10 working days.</li>
                    <li><strong>📧</strong> Confirmation email sent (check spam if not received).</li>
                    <li><strong>❓</strong> No update in 14 days? Contact support@immigration.gov.ng</li>
                </ul>
            </div>
            <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 2px dashed #ddd; font-size: 1em; color: #666;">
                <p><em>This is an official computer-generated acknowledgement. Keep this document for your records. No manual signature required.</em></p>
                <p>Nigeria Immigration Service © {{ date('Y') }}</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Registration Page --> 

<div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h2>New Registration</h2>
                        <p>Complete your profile and upload your documents</p>
                    </div>
                    <!-- <div class="welcome-action">
                        <a href="#" class="btn btn-primary modal-trigger" data-modal="register">
                            <i class="fas fa-plus"></i> New Application
                        </a>
                        <a href="#applications" class="btn" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="fas fa-list"></i> View All
                        </a>
                    </div> -->
                </div>
    <div class="col-md-12 col-lg-10 mx-auto mb-5">
    
      <!-- Progress Percentage Display -->
      <div class="step-progress">
        Progress: <span class="progress-percentage">0%</span> completed
      </div>

      <!-- Step Indicator -->
      <div class="step-indicator" data-progress="0">
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

      <div class="modal-body">
        <form id="registrationForm" method="post" enctype="multipart/form-data" novalidate>
        <!-- Step 1: Registration Form -->
        <div id="step1Form" style="display: block;">
            <div class="form-group">
              <label for="regSurname">Surname</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="regSurname" name="regSurname" placeholder="Enter surname" required value="<?php echo htmlspecialchars($old['regSurname'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="regFirstName">First Name</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="regFirstName" name="regFirstName" placeholder="Enter first name" required value="<?php echo htmlspecialchars($old['regFirstName'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="regOtherNames">Other Names</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="regOtherNames" name="regOtherNames" placeholder="Enter other names (optional)" value="<?php echo htmlspecialchars($old['regOtherNames'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="regPassport">Passport Number</label>
              <div class="input-wrapper">
                <i class="fas fa-passport"></i>
                <input type="text" id="regPassport" name="regPassport" placeholder="Enter passport number" required value="<?php echo htmlspecialchars($old['regPassport'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="regNationality">Nationality</label>
              <div class="input-wrapper">
                <i class="fas fa-flag"></i>
                <select id="regNationality" name="regNationality" required>
                  <option value="">Select nationality</option>
                  <?php foreach ($nationalities as $nat): ?>
                    <option value="<?php echo htmlspecialchars($nat); ?>" <?php echo (isset($old['regNationality']) && $old['regNationality'] === $nat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($nat); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="regPassportExpiry">Passport Expiry Date</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar"></i>
                <input type="date" id="regPassportExpiry" name="regPassportExpiry" required value="<?php echo htmlspecialchars($old['regPassportExpiry'] ?? ''); ?>">
              </div>
            </div>

            <button type="button" class="modal-btn" data-next-step="1">Next <i class="fas fa-arrow-right"></i></button>
        </div>

        <!-- Step 2: Travel Information -->
        <div id="step2Form" style="display: none;">
            <div class="form-group">
              <label for="visaCategory">Visa Category</label>
              <div class="input-wrapper">
                <i class="fas fa-visa"></i>
                <select id="visaCategory" name="visaCategory" required>
                  <option value="">Select Visa Category</option>
                  <option value="SVV" <?php echo (isset($old['visaCategory']) && $old['visaCategory']==='SVV')?'selected':''; ?>>Short Visit Visa (SVV)</option>
                  <option value="TRV" <?php echo (isset($old['visaCategory']) && $old['visaCategory']==='TRV')?'selected':''; ?>>Temporary Residence Visa (TRV)</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="svvCategory" style="display: none;">
              <label for="svvSubCat">SVV Sub-category</label>
              <div class="input-wrapper">
                <i class="fas fa-list"></i>
                <select id="svvSubCat" name="svvSubCat">
                  <option value="">Select Type</option>
                  <option value="F4A" <?php echo (isset($old['svvSubCat']) && $old['svvSubCat']==='F4A')?'selected':''; ?>>F4A</option>
                  <option value="F4B" <?php echo (isset($old['svvSubCat']) && $old['svvSubCat']==='F4B')?'selected':''; ?>>F4B</option>
                  <option value="F5A" <?php echo (isset($old['svvSubCat']) && $old['svvSubCat']==='F5A')?'selected':''; ?>>F5A</option>
                  <option value="F6A" <?php echo (isset($old['svvSubCat']) && $old['svvSubCat']==='F6A')?'selected':''; ?>>F6A</option>
                  <option value="">Select Type</option>
                  <option value="F4A">F4A</option>
                  <option value="F4B">F4B</option>
                  <option value="F5A">F5A</option>
                  <option value="F6A">F6A</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="trvCategory" style="display: none;">
              <label for="trvSubCat">TRV Sub-category</label>
              <div class="input-wrapper">
                <i class="fas fa-list"></i>
                <select id="trvSubCat" name="trvSubCat">
                  <option value="">Select Type</option>
                  <option value="R2A" <?php echo (isset($old['trvSubCat']) && $old['trvSubCat']==='R2A')?'selected':''; ?>>R2A</option>
                  <option value="R5A" <?php echo (isset($old['trvSubCat']) && $old['trvSubCat']==='R5A')?'selected':''; ?>>R5A</option>
                  <option value="">Select Type</option>
                  <option value="R2A">R2A</option>
                  <option value="R5A">R5A</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="arrivalDate">Arrival Date</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" id="arrivalDate" name="arrivalDate" required value="<?php echo htmlspecialchars($old['arrivalDate'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="addressNigeria">Address in Nigeria</label>
              <div class="input-wrapper">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="addressNigeria" name="addressNigeria" placeholder="Street address" required value="<?php echo htmlspecialchars($old['addressNigeria'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="city">City</label>
              <div class="input-wrapper">
                <i class="fas fa-city"></i>
                <input type="text" id="city" name="city" placeholder="City" required value="<?php echo htmlspecialchars($old['city'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="state">State</label>
              <div class="input-wrapper">
                <i class="fas fa-map"></i>
                <input type="text" id="state" name="state" placeholder="State" required value="<?php echo htmlspecialchars($old['state'] ?? ''); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="reasonRequest">Reason for Request</label>
              <div class="input-wrapper">
                <i class="fas fa-pen"></i>
                <textarea id="reasonRequest" name="reasonRequest" rows="3" placeholder="Explain your reason" required><?php echo htmlspecialchars($old['reasonRequest'] ?? ''); ?></textarea>
              </div>
            </div>

            <div style="display: flex; gap: 1rem;">
              <button type="button" class="modal-btn" data-prev-step="2" style="background: var(--gray);">Previous</button>
              <button type="button" class="modal-btn" data-next-step="2">Next <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 3: Document Upload -->
        <div id="step3Form" style="display: none;">
            <div class="form-group">
              <label for="passportDataFile">Passport Data Page</label>
              <div class="file-input-wrapper">
                <input type="file" id="passportDataFile" name="passportDataFile" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only</small>
            </div>

            <div class="form-group">
              <label for="visaFile">Entry Visa</label>
              <div class="file-input-wrapper">
                <input type="file" id="visaFile" name="visaFile" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only</small>
            </div>

            <div class="form-group">
              <label for="stampFile">Entry Stamp</label>
              <div class="file-input-wrapper">
                <input type="file" id="stampFile" name="stampFile" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only</small>
            </div>

            <div class="form-group">
              <label for="ticketFile">Return Ticket</label>
              <div class="file-input-wrapper">
                <input type="file" id="ticketFile" name="ticketFile" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only</small>
            </div>

            <div style="display: flex; gap: 1rem;">
              <button type="button" class="modal-btn" data-prev-step="3" style="background: var(--gray);">Previous</button>
              <button type="button" class="modal-btn" data-next-step="3">Next <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- Step 4: Review -->
        <div id="step4Form" style="display: none;">
          <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--primary);"></i>
            <h3 style="color: var(--secondary); margin: 1rem 0;">Review Your Information</h3>
            <p style="color: var(--gray);">Please review all information before submitting</p>
          </div>

          <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h4 style="color: var(--secondary); margin-bottom: 1rem;">Personal Information</h4>
            <p><strong>Surname:</strong> <span id="reviewSurname"></span></p>
            <p><strong>First Name:</strong> <span id="reviewFirstName"></span></p>
            <p><strong>Other Names:</strong> <span id="reviewOtherNames"></span></p>
            <p><strong>Passport:</strong> <span id="reviewPassport"></span></p>
            <p><strong>Nationality:</strong> <span id="reviewNationality"></span></p>
            <p><strong>Passport Expiry:</strong> <span id="reviewPassportExpiry"></span></p>
            <p><strong>Email:</strong> <span id="reviewEmail"></span></p>
          </div>

          <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h4 style="color: var(--secondary); margin-bottom: 1rem;">Travel Information</h4>
            <p><strong>Visa Category:</strong> <span id="reviewVisaCategory"></span></p>
            <p><strong>Arrival Date:</strong> <span id="reviewArrivalDate"></span></p>
            <p><strong>Address:</strong> <span id="reviewAddress"></span></p>
            <p><strong>City/State:</strong> <span id="reviewCityState"></span></p>
          </div>

          <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h4 style="color: var(--secondary); margin-bottom: 1rem;">Documents Uploaded</h4>
            <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Passport Data Page</p>
            <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Entry Visa</p>
            <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Entry Stamp</p>
            <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Return Ticket</p>
          </div>

          <div style="display: flex; gap: 1rem;">
            <button type="button" class="modal-btn" data-prev-step="4" style="background: var(--gray);">Edit</button>
            <button type="button" class="modal-btn" data-submit-application="true">Complete Application</button>
          </div>
        </div>
        </form>
@include ('partials.footer')