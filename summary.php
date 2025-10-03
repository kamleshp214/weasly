<?php
// summary.php
// Page 3: Show a summary reading from $_SESSION

session_start();

if (!isset($_SESSION['student'])) {
    header('Location: student_form.php');
    exit;
}

$s = $_SESSION['student'];

// Helper for safe output
function out($k) {
    global $s;
    return isset($s[$k]) ? htmlspecialchars($s[$k]) : '';
}

$scopes = isset($s['scopes']) && is_array($s['scopes']) ? $s['scopes'] : [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Registration Summary</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body { font-family: Arial, sans-serif; background:#f5f7fb; padding:20px; }
.container { max-width:900px; margin:0 auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
h1 { margin-top:0 }
table { width:100%; border-collapse:collapse; margin-top:12px }
th, td { text-align:left; padding:10px; border-bottom:1px solid #eee }
th { width:200px; background:#fafafa }
.badge { display:inline-block; padding:6px 10px; background:#eef2ff; border-radius:20px; margin-right:6px; margin-top:6px }
.actions { margin-top:16px }
button { padding:10px 14px; border-radius:8px; border:0; cursor:pointer }
button.primary { background:#2b6cb0; color:#fff }
</style>
</head>
<body>
<div class="container">
    <h1>Student Registration — Summary</h1>
    <p class="small">Below are the details you entered. You can go back to edit if needed.</p>

    <table>
        <tr><th>Full Name</th><td><?= out('full_name') ?></td></tr>
        <tr><th>Father's Name</th><td><?= out('father_name') ?></td></tr>
        <tr><th>Mother's Name</th><td><?= out('mother_name') ?></td></tr>
        <tr><th>Date of Birth</th><td><?= out('dob') ?></td></tr>
        <tr><th>Gender</th><td><?= out('gender') ?></td></tr>
        <tr><th>Contact</th><td><?= out('contact') ?></td></tr>
        <tr><th>Email</th><td><?= out('email') ?></td></tr>
        <tr><th>Address</th><td><?= out('address') ?>, <?= out('city') ?>, <?= out('state') ?> - <?= out('pin') ?></td></tr>
        <tr><th>Nationality</th><td><?= out('nationality') ?></td></tr>
        <tr><th>Grade / Class</th><td><?= out('grade') ?></td></tr>
        <tr><th>Previous School / College</th><td><?= out('prev_school') ?></td></tr>
        <tr><th>Preferred Career / Interest</th><td><?= out('career') ?></td></tr>
        <tr><th>Selected Stream</th><td><?= htmlspecialchars($s['stream'] ?? '') ?></td></tr>
        <tr><th>Selected Scopes</th>
            <td>
                <?php if (count($scopes) === 0): ?>
                    <em>No scopes selected</em>
                <?php else: ?>
                    <?php foreach ($scopes as $sc): ?>
                        <span class="badge"><?= htmlspecialchars($sc) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="actions">
        <form method="post" action="student_form.php" style="display:inline-block; margin-right:8px;">
            <button type="submit" class="primary">Edit Data</button>
        </form>
        <form method="post" action="clear.php" style="display:inline-block;">
            <button type="submit">Clear Session (start over)</button>
        </form>
    </div>
</div>
</body>
</html>
