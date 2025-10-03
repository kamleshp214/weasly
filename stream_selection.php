<?php
// Start the session to access any session data if needed
session_start();

// Check if form data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store student details from the form
    $student_data = [
        'name' => $_POST['name'],
        'father_name' => $_POST['father_name'],
        'mother_name' => $_POST['mother_name'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'pin_code' => $_POST['pin_code'],
        'mobile' => $_POST['mobile'],
        'email' => $_POST['email'],
        'aadhar' => $_POST['aadhar'],
        'roll_no' => $_POST['roll_no'],
        'class' => $_POST['class'],
        'section' => $_POST['section']
    ];
} else {
    // Redirect to the form if accessed directly without POST data
    header("Location: student_form.php");
    exit();
}

// Define career scopes for each stream
$scopes = [
    'Science (PCM)' => ['Engineering', 'Medical', 'Research', 'Data Science', 'Teaching'],
    'Commerce' => ['Chartered Accountancy (CA)', 'MBA', 'Banking', 'Finance', 'Business Analyst'],
    'Arts' => ['Journalism', 'Law', 'Civil Services', 'Teaching', 'Social Work']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stream Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .student-details {
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        select {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        button {
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Stream Selection</h2>
        
        <!-- Display submitted student details -->
        <div class="student-details">
            <h3>Student Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student_data['name']); ?></p>
            <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($student_data['father_name']); ?></p>
            <p><strong>Mother's Name:</strong> <?php echo htmlspecialchars($student_data['mother_name']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student_data['dob']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student_data['gender']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($student_data['address']); ?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($student_data['city']); ?></p>
            <p><strong>State:</strong> <?php echo htmlspecialchars($student_data['state']); ?></p>
            <p><strong>Pin Code:</strong> <?php echo htmlspecialchars($student_data['pin_code']); ?></p>
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($student_data['mobile']); ?></p>
            <p><strong>Email ID:</strong> <?php echo htmlspecialchars($student_data['email']); ?></p>
            <p><strong>Aadhar Number:</strong> <?php echo htmlspecialchars($student_data['aadhar']); ?></p>
            <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student_data['roll_no']); ?></p>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($student_data['class']); ?></p>
            <p><strong>Section:</strong> <?php echo htmlspecialchars($student_data['section']); ?></p>
        </div>

        <!-- Form to select stream -->
        <form action="" method="post">
            <!-- Retain student data in hidden inputs -->
            <?php foreach ($student_data as $key => $value) : ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
            <?php endforeach; ?>
            
            <label for="stream">Select Stream:</label>
            <select id="stream" name="stream" onchange="this.form.submit()" required>
                <option value="">Select a Stream</option>
                <option value="Science (PCM)" <?php echo isset($_POST['stream']) && $_POST['stream'] == 'Science (PCM)' ? 'selected' : ''; ?>>Science (PCM)</option>
                <option value="Commerce" <?php echo isset($_POST['stream']) && $_POST['stream'] == 'Commerce' ? 'selected' : ''; ?>>Commerce</option>
                <option value="Arts" <?php echo isset($_POST['stream']) && $_POST['stream'] == 'Arts' ? 'selected' : ''; ?>>Arts</option>
            </select>
        </form>

        <!-- Display career scopes based on selected stream -->
        <?php if (isset($_POST['stream']) && array_key_exists($_POST['stream'], $scopes)) : ?>
            <h3>Career Scopes for <?php echo htmlspecialchars($_POST['stream']); ?></h3>
            <ul>
                <?php foreach ($scopes[$_POST['stream']] as $scope) : ?>
                    <li><?php echo htmlspecialchars($scope); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>