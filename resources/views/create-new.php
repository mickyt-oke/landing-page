<?php
/**
 * User Dashboard - Migrants Overstay Portal
 * Nigeria Immigration Service
 */
session_start();

// Check if user is logged in (placeholder for actual auth)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: index.php');
//     exit;
// }

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
        // TODO: persist data to database and move uploaded files safely
        // e.g. move_uploaded_file($_FILES['passportDataFile']['tmp_name'], $destination);
        $successMessage = 'Application submitted successfully. You will receive a confirmation email shortly.';
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

<?php include 'inc/header.php'; ?>

<?php if (!empty($errors)): ?>
    <div class="form-messages error" role="alert">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php elseif (!empty($successMessage)): ?>
    <div class="form-messages success" role="status">
        <?php echo htmlspecialchars($successMessage); ?>
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
<?php include 'inc/footer.php'; ?>