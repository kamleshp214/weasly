<?php
// stream_selection.php
// Page 2: Let user choose a stream and the related scopes (careers).
// Uses session to read student info from Page 1 and to store stream/scopes for summary.

// Start session
session_start();

// Redirect back to form if no student info present
if (!isset($_SESSION['student'])) {
    header('Location: student_form.php');
    exit;
}

// Pre-define the mapping of streams to scope options
$stream_map = [
    'Science' => ['Engineering','Medical','Research','Data Science','Environmental Science'],
    'Commerce' => ['Chartered Accountant','Business Management','Economist','Banking','Accounting'],
    'Arts / Humanities' => ['Law','Journalism','Psychology','Literature','Political Science']
];

$errors = [];
$selectedStream = $_SESSION['stream'] ?? '';
$selectedScopes = $_SESSION['scopes'] ?? [];

// If form submitted, validate and save to session then redirect to summary.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedStream = isset($_POST['stream']) ? trim($_POST['stream']) : '';
    $selectedScopes = isset($_POST['scopes']) && is_array($_POST['scopes']) ? array_map('trim', $_POST['scopes']) : [];

    // Validate stream
    if ($selectedStream === '' || !array_key_exists($selectedStream, $stream_map)) {
        $errors['stream'] = "Please select a valid stream.";
    } else {
        // Validate that each selected scope belongs to the chosen stream (server-side security)
        $validScopes = $stream_map[$selectedStream];
        foreach ($selectedScopes as $s) {
            if (!in_array($s, $validScopes, true)) {
                $errors['scopes'] = "Invalid scope selection detected.";
                break;
            }
        }
    }

    // Optionally require at least one scope:
    // if (empty($selectedScopes)) { $errors['scopes'] = "Choose at least one scope/career option."; }

    if (empty($errors)) {
        $_SESSION['stream'] = $selectedStream;
        $_SESSION['scopes'] = $selectedScopes;
        header('Location: summary.php');
        exit;
    }
}

// For safe output
function e($s) { return htmlspecialchars($s, ENT_QUOTES); }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Registration - Stream Selection</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background:#f5f7fb; padding:20px; }
        .container { max-width:700px; margin:24px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        h1{margin-top:0}
        label{font-weight:600}
        select { width:100%; padding:8px 10px; margin:8px 0 14px; border-radius:6px; border:1px solid #cfd8e3; }
        .scopes { display:flex; flex-wrap:wrap; gap:10px; margin-top:6px; }
        .scope-item { background:#f1f6ff; padding:8px 10px; border-radius:6px; border:1px solid #e0ecff; }
        .error { color:#b00020; margin-top:6px; }
        .actions { margin-top:14px; display:flex; gap:10px; }
        button { padding:10px 14px; border-radius:6px; border:none; cursor:pointer; font-weight:600; }
        .primary { background:#0b74de; color:white; }
        .secondary { background:#eef3fb; color:#0b74de; }
        .student-summary { margin-top:12px; font-size:0.95rem; color:#334155; background:#fbfcff; padding:10px; border-radius:6px; border:1px solid #eef3fb; }
    </style>
    <script>
        // JS will show/hide scopes based on selected stream
        document.addEventListener('DOMContentLoaded', function(){
            const streamMap = {
                <?php
                $pairs = [];
                foreach ($stream_map as $k => $arr) {
                    $pairs[] = json_encode($k) . ':' . json_encode($arr);
                }
                echo implode(',', $pairs);
                ?>
            };
            const streamSelect = document.getElementById('stream');
            const scopesWrapper = document.getElementById('scopesWrapper');

            function renderScopesFor(stream) {
                scopesWrapper.innerHTML = '';
                if (!stream || !streamMap[stream]) return;
                const scopes = streamMap[stream];
                scopes.forEach(function(scope){
                    const id = 'scope_' + scope.replace(/\s+/g,'_');
                    const div = document.createElement('div');
                    div.className = 'scope-item';
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'scopes[]';
                    checkbox.value = scope;
                    checkbox.id = id;
                    // Pre-check if already selected (server-reserved via HTML data)
                    const pre = document.querySelector('input[name="__preselected_scopes"]');
                    if (pre) {
                        try {
                            const prearray = JSON.parse(pre.value);
                            if (prearray.indexOf(scope) !== -1) checkbox.checked = true;
                        } catch(e){}
                    }
                    const label = document.createElement('label');
                    label.htmlFor = id;
                    label.style.marginLeft = '6px';
                    label.innerText = scope;

                    div.appendChild(checkbox);
                    div.appendChild(label);
                    scopesWrapper.appendChild(div);
                });
            }

            // initial render based on selected stream (if any)
            renderScopesFor(streamSelect.value);

            streamSelect.addEventListener('change', function(){
                renderScopesFor(this.value);
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h1>Step 2 — Stream &amp; Career Scopes</h1>

    <div class="student-summary">
        <strong>Student:</strong> <?=e($_SESSION['student']['full_name'])?> — <?=e($_SESSION['student']['current_grade'])?><br>
        <small style="color:#6b7280">Email: <?=e($_SESSION['student']['email'])?> | Contact: <?=e($_SESSION['student']['contact'])?></small>
    </div>

    <form method="post" novalidate style="margin-top:12px;">
        <label for="stream">Select Stream *</label>
        <select id="stream" name="stream" required>
            <option value="">-- Select a stream --</option>
            <?php foreach ($stream_map as $streamName => $list): 
                $sel = ($selectedStream === $streamName) ? 'selected' : '';
            ?>
                <option value="<?=e($streamName)?>" <?=$sel?>><?=e($streamName)?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['stream'])): ?><div class="error"><?=$errors['stream']?></div><?php endif; ?>

        <label style="margin-top:10px;">Choose related scope/career options (checkboxes)</label>
        <div id="scopesWrapper" class="scopes" aria-live="polite"></div>
        <?php if (isset($errors['scopes'])): ?><div class="error"><?=$errors['scopes']?></div><?php endif; ?>

        <!-- Hidden input to pass preselected scopes for JS rendering -->
        <input type="hidden" name="__preselected_scopes" value='<?=json_encode($selectedScopes)?>'>

        <div class="actions">
            <button type="submit" class="primary">Save &amp; Continue →</button>
            <a href="student_form.php"><button type="button" class="secondary">Edit Student Info</button></a>
        </div>
    </form>

    <p style="margin-top:12px;color:#667085;font-size:0.92rem">
        Tip: The list of scope options changes based on the chosen stream. Server-side validation ensures selected scopes belong to the chosen stream.
    </p>
</div>
</body>
</html>
