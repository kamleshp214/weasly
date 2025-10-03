<?php
// student_form.php
// Page 1: Collects student information and stores it in $_SESSION for the next page.

// Start session so we can save data and show validation errors across requests
session_start();

// Initialize variables and error array
$errors = [];
$values = [
    'full_name'=>'', 'father_name'=>'', 'mother_name'=>'', 'dob'=>'', 'gender'=>'',
    'contact'=>'', 'email'=>'', 'address'=>'', 'city'=>'', 'state'=>'', 'pincode'=>'',
    'nationality'=>'', 'current_grade'=>'', 'previous_school'=>'', 'career_interest'=>''
];

// If the form was submitted, validate and save into session, then redirect to stream_selection.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and assign posted values
    foreach ($values as $k => $v) {
        $values[$k] = isset($_POST[$k]) ? trim($_POST[$k]) : '';
    }

    // Validation
    if ($values['full_name'] === '') {
        $errors['full_name'] = "Full name is required.";
    }
    if ($values['father_name'] === '') {
        $errors['father_name'] = "Father's name is required.";
    }
    if ($values['mother_name'] === '') {
        $errors['mother_name'] = "Mother's name is required.";
    }
    // DOB validation: required & valid date (YYYY-MM-DD)
    if ($values['dob'] === '') {
        $errors['dob'] = "Date of birth is required.";
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $values['dob']);
        $d_errors = DateTime::getLastErrors();
        if (!$d || $d_errors['warning_count'] + $d_errors['error_count'] > 0) {
            $errors['dob'] = "Invalid date format. Use the date picker.";
        }
    }
    // Gender required
    if (!in_array($values['gender'], ['Male','Female','Other'], true)) {
        $errors['gender'] = "Select a valid gender.";
    }
    // Contact validation (India-like 10 digits) - accept +, spaces, dashes optionally
    $normalizedContact = preg_replace('/[^0-9]/','',$values['contact']);
    if ($values['contact'] === '') {
        $errors['contact'] = "Contact number is required.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $normalizedContact)) {
        $errors['contact'] = "Enter a valid contact number (10-15 digits).";
    } else {
        // store normalized number for consistency
        $values['contact'] = $normalizedContact;
    }
    // Email validation
    if ($values['email'] === '') {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }
    // Address fields
    if ($values['address'] === '') {
        $errors['address'] = "Address is required.";
    }
    if ($values['city'] === '') {
        $errors['city'] = "City is required.";
    }
    if ($values['state'] === '') {
        $errors['state'] = "State is required.";
    }
    // Pin code: 4-6 digits typical
    if ($values['pincode'] === '') {
        $errors['pincode'] = "Pin code is required.";
    } elseif (!preg_match('/^[0-9]{4,6}$/', $values['pincode'])) {
        $errors['pincode'] = "Enter a valid pin code (4-6 digits).";
    }
    if ($values['nationality'] === '') {
        $errors['nationality'] = "Nationality is required.";
    }
    if ($values['current_grade'] === '') {
        $errors['current_grade'] = "Current grade/class is required.";
    }
    if ($values['previous_school'] === '') {
        $errors['previous_school'] = "Previous school/college is required.";
    }
    // career_interest is optional

    // If no errors, save to session and redirect to stream_selection.php
    if (empty($errors)) {
        $_SESSION['student'] = $values;
        // Clear any previous stream selection
        unset($_SESSION['stream']);
        unset($_SESSION['scopes']);
        header('Location: stream_selection.php');
        exit;
    } else {
        // Save values back to session temporarily so fields can persist (optional)
        $_SESSION['student_temp_values'] = $values;
    }
} else {
    // If user visited fresh and session has saved student (e.g., editing), prefill values
    if (isset($_SESSION['student'])) {
        $values = array_merge($values, $_SESSION['student']);
    } elseif (isset($_SESSION['student_temp_values'])) {
        $values = array_merge($values, $_SESSION['student_temp_values']);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Registration - Step 1</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        /* Basic clean CSS layout */
        body { font-family: Arial, sans-serif; background:#f5f7fb; margin:0; padding:20px; }
        .container { max-width:900px; margin:20px auto; background:#fff; padding:22px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        h1{margin-top:0}
        form .row { display:flex; gap:12px; margin-bottom:12px; }
        .col { flex:1; min-width:150px; }
        label { display:block; font-weight:600; margin-bottom:6px; font-size:0.95rem; }
        input[type="text"], input[type="email"], input[type="date"], textarea, select {
            width:100%; padding:8px 10px; border:1px solid #cfd8e3; border-radius:6px;
            font-size:0.95rem;
        }
        textarea { min-height:70px; resize:vertical; }
        .error { color:#b00020; font-size:0.88rem; margin-top:6px; }
        .actions { display:flex; gap:10px; margin-top:16px; }
        button { padding:10px 16px; border-radius:6px; border:none; font-weight:600; cursor:pointer; }
        button.primary { background:#0b74de; color:white; }
        button.reset { background:#e6eefc; color:#0b74de; }
        .note { color:#5f6b7a; font-size:0.92rem; margin-bottom:8px; }
        @media (max-width:640px) { .row { flex-direction:column; } }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Registration — Step 1</h1>
    <p class="note">Fill the details below. Fields marked required will be validated.</p>

    <form method="post" novalidate>
        <!-- Row 1: Full Name -->
        <div class="row">
            <div class="col">
                <label for="full_name">Full Name *</label>
                <input id="full_name" name="full_name" type="text" value="<?=htmlspecialchars($values['full_name'])?>" required>
                <?php if (isset($errors['full_name'])): ?><div class="error"><?=$errors['full_name']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="father_name">Father's Name *</label>
                <input id="father_name" name="father_name" type="text" value="<?=htmlspecialchars($values['father_name'])?>" required>
                <?php if (isset($errors['father_name'])): ?><div class="error"><?=$errors['father_name']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row">
            <div class="col">
                <label for="mother_name">Mother's Name *</label>
                <input id="mother_name" name="mother_name" type="text" value="<?=htmlspecialchars($values['mother_name'])?>" required>
                <?php if (isset($errors['mother_name'])): ?><div class="error"><?=$errors['mother_name']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="dob">Date of Birth *</label>
                <input id="dob" name="dob" type="date" value="<?=htmlspecialchars($values['dob'])?>" required>
                <?php if (isset($errors['dob'])): ?><div class="error"><?=$errors['dob']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 3 Gender & Contact -->
        <div class="row">
            <div class="col">
                <label for="gender">Gender *</label>
                <select id="gender" name="gender" required>
                    <option value="">-- Select --</option>
                    <?php
                        $glist = ['Male','Female','Other'];
                        foreach($glist as $g) {
                            $sel = ($values['gender'] === $g) ? 'selected' : '';
                            echo "<option value=\"$g\" $sel>$g</option>";
                        }
                    ?>
                </select>
                <?php if (isset($errors['gender'])): ?><div class="error"><?=$errors['gender']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="contact">Contact Number *</label>
                <input id="contact" name="contact" type="text" placeholder="Digits only (10-15)" value="<?=htmlspecialchars($values['contact'])?>" required>
                <?php if (isset($errors['contact'])): ?><div class="error"><?=$errors['contact']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 4 Email & Address -->
        <div class="row">
            <div class="col">
                <label for="email">Email Address *</label>
                <input id="email" name="email" type="email" value="<?=htmlspecialchars($values['email'])?>" required>
                <?php if (isset($errors['email'])): ?><div class="error"><?=$errors['email']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="address">Address *</label>
                <textarea id="address" name="address" required><?=htmlspecialchars($values['address'])?></textarea>
                <?php if (isset($errors['address'])): ?><div class="error"><?=$errors['address']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 5 City State Pincode -->
        <div class="row">
            <div class="col">
                <label for="city">City *</label>
                <input id="city" name="city" type="text" value="<?=htmlspecialchars($values['city'])?>" required>
                <?php if (isset($errors['city'])): ?><div class="error"><?=$errors['city']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="state">State *</label>
                <input id="state" name="state" type="text" value="<?=htmlspecialchars($values['state'])?>" required>
                <?php if (isset($errors['state'])): ?><div class="error"><?=$errors['state']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="pincode">Pin Code *</label>
                <input id="pincode" name="pincode" type="text" value="<?=htmlspecialchars($values['pincode'])?>" required>
                <?php if (isset($errors['pincode'])): ?><div class="error"><?=$errors['pincode']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 6 Nationality & Grade -->
        <div class="row">
            <div class="col">
                <label for="nationality">Nationality *</label>
                <input id="nationality" name="nationality" type="text" value="<?=htmlspecialchars($values['nationality'])?>" required>
                <?php if (isset($errors['nationality'])): ?><div class="error"><?=$errors['nationality']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="current_grade">Current Grade/Class *</label>
                <input id="current_grade" name="current_grade" type="text" value="<?=htmlspecialchars($values['current_grade'])?>" required>
                <?php if (isset($errors['current_grade'])): ?><div class="error"><?=$errors['current_grade']?></div><?php endif; ?>
            </div>
            <div class="col">
                <label for="previous_school">Previous School/College *</label>
                <input id="previous_school" name="previous_school" type="text" value="<?=htmlspecialchars($values['previous_school'])?>" required>
                <?php if (isset($errors['previous_school'])): ?><div class="error"><?=$errors['previous_school']?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 7 Optional career interest -->
        <div class="row">
            <div class="col">
                <label for="career_interest">Preferred Career / Interest (optional)</label>
                <input id="career_interest" name="career_interest" type="text" value="<?=htmlspecialchars($values['career_interest'])?>">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="primary">Submit &amp; Continue →</button>
            <button type="reset" class="reset">Reset Form</button>
        </div>
    </form>

    <p style="margin-top:14px;font-size:0.9rem;color:#596370">
        Note: This demo uses PHP sessions to pass data between pages. Server-side validation is performed.
    </p>
</div>
</body>
</html>
