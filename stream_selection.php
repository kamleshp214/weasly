<?php
// stream_selection.php
// Page 2: Choose a stream and related scopes. Saves selections in session and goes to summary.php

session_start();

// Ensure student data from page 1 exists; if not, redirect back
if (!isset($_SESSION['student'])) {
    header('Location: student_form.php');
    exit;
}

$errors = [];
$old = [
    'stream' => '',
    'scopes' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['stream'] = isset($_POST['stream']) ? trim($_POST['stream']) : '';
    $old['scopes'] = isset($_POST['scopes']) && is_array($_POST['scopes']) ? $_POST['scopes'] : [];

    if ($old['stream'] === '') {
        $errors['stream'] = 'Please select a stream';
    } else {
        // Validate scopes belong to stream server-side
        $validScopes = [
            'Science' => ['Engineering','Medical','Research','Data Science','Environmental Science'],
            'Commerce' => ['Chartered Accountant','Business Management','Economist','Banking','Accounting'],
            'Arts' => ['Law','Journalism','Psychology','Literature','Political Science'],
            'Arts / Humanities' => ['Law','Journalism','Psychology','Literature','Political Science']
        ];

        $allowed = $validScopes[$old['stream']] ?? [];

        // filter posted scopes to allowed
        $old['scopes'] = array_values(array_filter($old['scopes'], function($s) use ($allowed) {
            return in_array($s, $allowed, true);
        }));

        // Save to session and go to summary
        $_SESSION['student']['stream'] = $old['stream'];
        $_SESSION['student']['scopes'] = $old['scopes'];

        header('Location: summary.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Stream Selection - Step 2</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body { font-family: Arial, sans-serif; background:#f5f7fb; padding:20px; }
.container { max-width:800px; margin:0 auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
h1 { margin-top:0 }
.form-row { margin-bottom:12px; }
.checkbox-list { display:flex; flex-wrap:wrap; gap:10px; margin-top:8px }
.checkbox-item { background:#f1f5f9; padding:8px 10px; border-radius:6px; }
.error { color:#b00020; font-size:13px }
button.primary { background:#2b6cb0; color:#fff; padding:10px 14px; border-radius:8px; border:0; cursor:pointer }
.small { font-size:13px; color:#666 }
</style>
<script>
// Client-side mapping for dynamic UI
const scopesMap = {
    'Science': ['Engineering','Medical','Research','Data Science','Environmental Science'],
    'Commerce': ['Chartered Accountant','Business Management','Economist','Banking','Accounting'],
    'Arts / Humanities': ['Law','Journalism','Psychology','Literature','Political Science'],
    'Arts': ['Law','Journalism','Psychology','Literature','Political Science']
};

function renderScopes(stream) {
    const container = document.getElementById('scopesContainer');
    container.innerHTML = '';
    if (!stream) return;
    const list = scopesMap[stream] || [];
    list.forEach(s => {
        const id = 'scope_' + s.replace(/\s+/g,'_');
        const div = document.createElement('div');
        div.className = 'checkbox-item';
        div.innerHTML = `<label><input type="checkbox" name="scopes[]" value="${s}" id="${id}"> ${s}</label>`;
        container.appendChild(div);
    });
}

// On page load, if stream preselected, render and check
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.querySelector('select[name="stream"]');
    if (sel && sel.value) {
        renderScopes(sel.value);

        // restore any previously selected scopes from data attribute
        const pre = container.getAttribute('data-preselected');
        if (pre) {
            try {
                const arr = JSON.parse(pre);
                arr.forEach(v => {
                    const el = document.querySelector(`input[name="scopes[]"][value="${v}"]`);
                    if (el) el.checked = true;
                });
            } catch(e) {}
        }
    }
});
</script>
</head>
<body>
<div class="container">
    <h1>Stream & Career Scope — Step 2</h1>
    <p class="small">Select a stream. Only related scopes will be shown. Select multiple scopes if applicable.</p>

    <?php if (!empty($errors)): ?>
        <div class="error"><strong>Fix the errors below.</strong></div>
    <?php endif; ?>

    <form method="post" action="stream_selection.php" onsubmit="return true;">
        <div class="form-row">
            <label><strong>Stream *</strong></label><br>
            <?php $sel = $old['stream'] ?? ''; ?>
            <select name="stream" onchange="renderScopes(this.value)">
                <option value="">-- Select Stream --</option>
                <option value="Science" <?= $sel==='Science' ? 'selected' : '' ?>>Science</option>
                <option value="Commerce" <?= $sel==='Commerce' ? 'selected' : '' ?>>Commerce</option>
                <option value="Arts / Humanities" <?= ($sel==='Arts / Humanities' || $sel==='Arts') ? 'selected' : '' ?>>Arts / Humanities</option>
            </select>
            <?php if(!empty($errors['stream'])): ?><div class="error"><?= $errors['stream'] ?></div><?php endif; ?>
        </div>

        <div class="form-row">
            <label><strong>Possible Career Scopes</strong></label>
            <!-- The container will be populated by JS. For server-side support we also show all as fallback (progressive enhancement) -->
            <div id="scopesContainer" data-preselected='<?= json_encode($old['scopes'] ?? []) ?>' class="checkbox-list" style="margin-top:8px;">
                <!-- Fallback: show all grouped by stream names (but JS will replace) -->
                <div class="checkbox-item"><strong>Science:</strong> Engineering, Medical, Research, Data Science, Environmental Science</div>
                <div class="checkbox-item"><strong>Commerce:</strong> Chartered Accountant, Business Management, Economist, Banking, Accounting</div>
                <div class="checkbox-item"><strong>Arts / Humanities:</strong> Law, Journalism, Psychology, Literature, Political Science</div>
            </div>
            <div class="small">Tip: if checkboxes don't appear, enable JavaScript or scroll — server will still validate.</div>
        </div>

        <div style="margin-top:16px;">
            <button type="submit" class="primary">Submit → Show Summary</button>
        </div>
    </form>

    <div style="margin-top:16px;">
        <a href="student_form.php" class="small">← Back to Step 1 (edit student data)</a>
    </div>
</div>

<script>
// Extra: after initial load, we need to ensure preselected scopes are re-checked when JS builds checkboxes
(function() {
    const sel = document.querySelector('select[name="stream"]');
    const pre = document.getElementById('scopesContainer').getAttribute('data-preselected');
    sel.addEventListener('change', function() {
        renderScopes(this.value);
        // try to re-check previously chosen scopes (if any)
        if (pre) {
            try {
                const arr = JSON.parse(pre);
                setTimeout(() => {
                    arr.forEach(v => {
                        const el = document.querySelector(`input[name="scopes[]"][value="${v}"]`);
                        if (el) el.checked = true;
                    });
                }, 50);
            } catch(e) {}
        }
    });

    // If a stream was already set on the server, render it now
    if (sel.value) {
        renderScopes(sel.value);
        // restore preselected choices
        try {
            const arr = JSON.parse(pre);
            arr.forEach(v => {
                const el = document.querySelector(`input[name="scopes[]"][value="${v}"]`);
                if (el) el.checked = true;
            });
        } catch(e){}
    }
})();
</script>
</body>
</html>
