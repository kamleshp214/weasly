<?php
// Start the session to allow data to be passed between pages if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        input, select {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }
        .button-group {
            text-align: center;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Student Details Form</h2>
        <!-- Form to collect student details -->
        <form action="stream_selection.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" required><br>

            <label for="mother_name">Mother's Name:</label>
            <input type="text" id="mother_name" name="mother_name" required><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required><br>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required><br>

            <label for="pin_code">Pin Code:</label>
            <input type="text" id="pin_code" name="pin_code" pattern="\d{6}" title="Enter a 6-digit pin code" required><br>

            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" pattern="\d{10}" title="Enter a 10-digit mobile number" required><br>

            <label for="email">Email ID:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="aadhar">Aadhar Number:</label>
            <input type="text" id="aadhar" name="aadhar" pattern="\d{12}" title="Enter a 12-digit Aadhar number" required><br>

            <label for="roll_no">Roll Number:</label>
            <input type="text" id="roll_no" name="roll_no" required><br>

            <label for="class">Class:</label>
            <input type="text" id="class" name="class" required><br>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" required><br>

            <div class="button-group">
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>