<?php
// summary.php
// Optional Page 3: Displays all stored student data + chosen stream + scopes.
// Start session and ensure data exists
session_start();

if (!isset($_SESSION['student'])) {
    header('Location: student_form.php');
    exit;
}

// Fetch data safely
$student = $_SESSION['student'];
$stream = $_SESSION['stream'] ?? '(not selected)';
$scopes = $_SESSION['scopes'] ?? [];

function e($s) { return htmlspecialchars($s, ENT_QUOTES); }

// Optional: Provide a 'clear' action to reset session (for demo/testing)
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    session_unset();
    session_destroy();
    header('Location: student_form.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registration Summary</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background:#f5f7fb; padding:20px; }
        .container { max-width:900px; margin:20px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        h1{margin-top:0}
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th, td { text-align:left; padding:10px 12px; border-bottom:1px solid #eef2f7; }
        th { width:240px; background:#fbfdff; font-weight:700; color:#0f172a; }
        .actions { margin-top:16px; display:flex; gap:8px; }
        .btn { padding:10px 14px; border-radius:6px; border:none; cursor:pointer; font-weight:600; text-decoration:none; display:inline-block; }
        .edit { background:#eef3fb; color:#0b74de; }
        .clear { background:#fee2e2; color:#b00020; }
    </style>
</head>
<body>
<div class="container">
    <h1>Registration Summary</h1>
    <p style="color:#475569">Review the submitted information below. You can edit details if needed.</p>

    <table>
        <tr><th>Full Name</th><td><?=e($student['full_name'])?></td></tr>
        <tr><th>Father's Name</th><td><?=e($student['father_name'])?></td></tr>
        <tr><th>Mother's Name</th><td><?=e($student['mother_name'])?></td></tr>
        <tr><th>Date of Birth</th><td><?=e($student['dob'])?></td></tr>
        <tr><th>Gender</th><td><?=e($student['gender'])?></td></tr>
        <tr><th>Contact</th><td><?=e($student['contact'])?></td></tr>
        <tr><th>Email</th><td><?=e($student['email'])?></td></tr>
        <tr><th>Address</th><td><?=e($student['address']).', '.e($student['city']).', '.e($student['state']).' - '.e($student['pincode'])?></td></tr>
        <tr><th>Nationality</th><td><?=e($student['nationality'])?></td></tr>
        <tr><th>Current Grade/Class</th><td><?=e($student['current_grade'])?></td></tr>
        <tr><th>Previous School/College</th><td><?=e($student['previous_school'])?></td></tr>
        <tr><th>Preferred Career / Interest</th><td><?=e($student['career_interest']) ?: '(not provided)'?></td></tr>
        <tr><th>Selected Stream</th><td><?=e($stream)?></td></tr>
        <tr><th>Selected Scopes / Careers</th><td><?=!empty($scopes) ? e(implode(', ', $scopes)) : '(none selected)'?></td></tr>
    </table>

    <div class="actions">
        <a href="student_form.php" class="btn edit">Edit Student Info</a>
        <a href="stream_selection.php" class="btn edit">Edit Stream/Scopes</a>
        <a href="?action=clear" class="btn clear" onclick="return confirm('Clear session and start over?')">Clear &amp; Start Over</a>
    </div>

    <p style="margin-top:12px;color:#6b7280">All data stored temporarily in PHP session. In real apps, you would save this into a database.</p>
</div>
</body>
</html>
