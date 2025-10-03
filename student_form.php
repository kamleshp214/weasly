<?php
// student_form.php
// Page 1: Collect student details, validate, save to session, forward to stream_selection.php

session_start();

// Initialize errors array and old values for sticky form behavior
$errors = [];
$old = [];

// If form submitted, validate server-side
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper to trim & store
    function gv($k) { return isset($_POST[$k]) ? trim($_POST[$k]) : ''; }

    $fields = [
        'full_name','father_name','mother_name','dob','gender','contact','email',
        'address','city','state','pin','nationality','grade','prev_school','career'
    ];

    foreach ($fields as $f) $old[$f] = gv($f);

    // Mandatory fields to check (career is optional)
    $required = [
        'full_name','father_name','mother_name','dob','gender','contact','email',
        'address','city','state','pin','nationality','grade','prev_school'
    ];

    foreach ($required as $r) {
        if ($old[$r] === '') $errors[$r] = 'This field is required';
    }

    // Email format
    if (!isset($errors['email']) && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address';
    }

    // Contact number: allow digits, 10-15 length (India usually 10)
    if (!isset($errors['contact'])) {
        $cleanContact = preg_replace('/\D+/', '', $old['contact']);
        if (strlen($cleanContact) < 7 || strlen($cleanContact) > 15) {
            $errors['contact'] = 'Enter a valid contact number (7-15 digits)';
        } else {
            $old['contact'] = $cleanContact;
        }
    }

    // Pin code: digits only (India 6 digits typically)
    if (!isset($errors['pin']) && !preg_match('/^\d{4,8}$/', $old['pin'])) {
        $errors['pin'] = 'Enter a valid pin/zip (4-8 digits)';
    }

    // DOB: basic YYYY-MM-DD check
    if (!isset($errors['dob'])) {
        $d = $old['dob'];
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) || !strtotime($d)) {
            $errors['dob'] = 'Enter DOB in YYYY-MM-DD';
        }
    }

    // If no errors, store in session and redirect to stream selection
    if (empty($errors)) {
        $_SESSION['student'] = [
            'full_name' => $old['full_name'],
            'father_name' => $old['father_name'],
            'mother_name' => $old['mother_name'],
            'dob' => $old['dob'],
            'gender' => $old['gender'],
            'contact' => $old['contact'],
            'email' => $old['email'],
            'address' => $old['address'],
            'city' => $old['city'],
            'state' => $old['state'],
            'pin' => $old['pin'],
            'nationality' => $old['nationality'],
            'grade' => $old['grade'],
            'prev_school' => $old['prev_school'],
            'career' => $old['career']
        ];

        // Redirect to next page
        header('Location: stream_selection.php');
        exit;
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
/* Basic clean CSS */
body { font-family: Arial, sans-serif; background:#f5f7fb; padding:20px; }
.container { max-width:900px; margin:0 auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
h1 { margin-top:0 }
.form-row { display:flex; gap:12px; margin-bottom:12px; }
.form-col { flex:1; display:flex; flex-direction:column; }
label { font-weight:600; margin-bottom:6px; font-size:14px }
input[type=text], input[type=email], input[type=date], textarea, select { padding:8px; border:1px solid #ccc; border-radius:6px; font-size:14px }
textarea { resize:vertical; min-height:80px; }
.error { color:#b00020; font-size:13px; margin-top:6px }
.actions { display:flex; gap:10px; margin-top:16px; }
button { padding:10px 16px; border-radius:8px; border:0; cursor:pointer; font-weight:600 }
button.primary { background:#2b6cb0; color:#fff }
button.secondary { background:#eee }
.small { font-size:13px; color:#666; margin-top:6px }
</style>
</head>
<body>
<div class="container">
    <h1>Student Registration — Step 1</h1>
    <p class="small">Fill required fields and click Submit to continue to stream selection.</p>

    <!-- Show top-level error summary if desired -->
    <?php if (!empty($errors)): ?>
        <div class="error"><strong>There are errors in the form. Please fix them below.</strong></div>
    <?php endif; ?>

    <form method="post" action="student_form.php" novalidate>
        <div class="form-row">
            <div class="form-col">
                <label>Full Name *</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                <?php if(!empty($errors['full_name'])): ?><div class="error"><?= $errors['full_name'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>Father's Name *</label>
                <input type="text" name="father_name" value="<?= htmlspecialchars($old['father_name'] ?? '') ?>">
                <?php if(!empty($errors['father_name'])): ?><div class="error"><?= $errors['father_name'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Mother's Name *</label>
                <input type="text" name="mother_name" value="<?= htmlspecialchars($old['mother_name'] ?? '') ?>">
                <?php if(!empty($errors['mother_name'])): ?><div class="error"><?= $errors['mother_name'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>Date of Birth *</label>
                <input type="date" name="dob" value="<?= htmlspecialchars($old['dob'] ?? '') ?>">
                <?php if(!empty($errors['dob'])): ?><div class="error"><?= $errors['dob'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Gender *</label>
                <select name="gender">
                    <option value="">-- Select --</option>
                    <?php $g = $old['gender'] ?? ''; ?>
                    <option <?= $g==='Male' ? 'selected' : '' ?>>Male</option>
                    <option <?= $g==='Female' ? 'selected' : '' ?>>Female</option>
                    <option <?= $g==='Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <?php if(!empty($errors['gender'])): ?><div class="error"><?= $errors['gender'] ?></div><?php endif; ?>
            </div>

            <div class="form-col">
                <label>Contact Number *</label>
                <input type="text" name="contact" placeholder="digits only" value="<?= htmlspecialchars($old['contact'] ?? '') ?>">
                <?php if(!empty($errors['contact'])): ?><div class="error"><?= $errors['contact'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Email Address *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <?php if(!empty($errors['email'])): ?><div class="error"><?= $errors['email'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>Address *</label>
                <textarea name="address"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                <?php if(!empty($errors['address'])): ?><div class="error"><?= $errors['address'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>City *</label>
                <input type="text" name="city" value="<?= htmlspecialchars($old['city'] ?? '') ?>">
                <?php if(!empty($errors['city'])): ?><div class="error"><?= $errors['city'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>State *</label>
                <input type="text" name="state" value="<?= htmlspecialchars($old['state'] ?? '') ?>">
                <?php if(!empty($errors['state'])): ?><div class="error"><?= $errors['state'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Pin Code *</label>
                <input type="text" name="pin" value="<?= htmlspecialchars($old['pin'] ?? '') ?>">
                <?php if(!empty($errors['pin'])): ?><div class="error"><?= $errors['pin'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>Nationality *</label>
                <input type="text" name="nationality" value="<?= htmlspecialchars($old['nationality'] ?? '') ?>">
                <?php if(!empty($errors['nationality'])): ?><div class="error"><?= $errors['nationality'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label>Current Grade / Class *</label>
                <input type="text" name="grade" placeholder="e.g., 12th, B.Sc, 3rd Year" value="<?= htmlspecialchars($old['grade'] ?? '') ?>">
                <?php if(!empty($errors['grade'])): ?><div class="error"><?= $errors['grade'] ?></div><?php endif; ?>
            </div>
            <div class="form-col">
                <label>Previous School / College *</label>
                <input type="text" name="prev_school" value="<?= htmlspecialchars($old['prev_school'] ?? '') ?>">
                <?php if(!empty($errors['prev_school'])): ?><div class="error"><?= $errors['prev_school'] ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="flex:1 1 100%">
                <label>Preferred Career / Interest (optional)</label>
                <input type="text" name="career" value="<?= htmlspecialchars($old['career'] ?? '') ?>">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="primary">Submit →</button>
            <button type="reset" class="secondary" onclick="location.href='student_form.php'">Reset</button>
        </div>
    </form>
</div>
</body>
</html>
