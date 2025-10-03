# Student Data Collection and Stream Selection Project

## Project Overview
This is a simple PHP project designed for beginners to demonstrate a two-page web application. The project collects student details through a form and allows the student to select an academic stream, displaying relevant career options based on the selection.

### What the Project Does
- **Page 1 (student_form.php)**: A form to collect 15 fields of student details (e.g., Name, Father's Name, Date of Birth, etc.). It includes a Submit button to send data to the second page and a Reset button to clear the form.
- **Page 2 (stream_selection.php)**: Displays the submitted student details and provides a dropdown to select a stream (Science, Commerce, or Arts). Based on the selected stream, it dynamically shows five career scope options.

### Pages Included
1. **student_form.php**: The input form for collecting student details.
2. **stream_selection.php**: The stream selection form and career scope display.
3. **README.md**: This file, explaining the project setup and usage.

## Requirements
- **XAMPP**: A free and open-source software to run PHP and Apache server locally.
- **Web Browser**: Any modern browser (e.g., Chrome, Firefox).
- **Basic Text Editor**: Notepad, VS Code, or any editor to view/edit the PHP files.

## Installation of XAMPP (Step-by-Step for Windows)
1. **Download XAMPP**:
   - Visit [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   - Download the latest version of XAMPP for Windows (e.g., XAMPP for Windows 8.2.12 with PHP 8).
   - Choose the installer (.exe) file for easier setup.
2. **Install XAMPP**:
   - Run the downloaded `.exe` file.
   - If prompted by Windows User Account Control (UAC), click "Yes."
   - Follow the setup wizard:
     - Select components: Ensure "Apache" and "PHP" are checked (others are optional).
     - Choose installation directory (default is `C:\xampp`).
     - Complete the installation process (takes a few minutes).
3. **Verify Installation**:
   - Navigate to `C:\xampp` (or your chosen directory).
   - Ensure the `htdocs` folder and `xampp-control.exe` are present.

## Starting Apache Server
1. Open the **XAMPP Control Panel**:
   - Find `xampp-control.exe` in `C:\xampp` and double-click to launch it.
2. Start the **Apache** module:
   - In the XAMPP Control Panel, locate the "Apache" row.
   - Click the "Start" button next to it.
   - The button should turn green, and the port numbers (e.g., 80, 443) will appear, indicating Apache is running.
3. Test the server:
   - Open a browser and navigate to `http://localhost`.
   - If you see the XAMPP dashboard, the server is running correctly.

## Where to Place Project Files
1. Locate the `htdocs` folder:
   - Path: `C:\xampp\htdocs`.
2. Create a project folder:
   - Inside `htdocs`, create a new folder named `student_project`.
3. Copy the project files:
   - Place the following files inside `C:\xampp\htdocs\student_project`:
     - `student_form.php`
     - `stream_selection.php`
     - `README.md`

## Project Structure
```
student_project/
├── student_form.php
├── stream_selection.php
├── README.md
```

## How to Run
1. **Install XAMPP**: Follow the installation steps above.
2. **Start Apache**: Open XAMPP Control Panel and start the Apache server.
3. **Copy Project Files**:
   - Copy the `student_project` folder (containing `student_form.php`, `stream_selection.php`, and `README.md`) into `C:\xampp\htdocs`.
4. **Access the Application**:
   - Open a web browser.
   - Navigate to `http://localhost/student_project/student_form.php`.
5. **Use the Application**:
   - Fill in the student details in the form on `student_form.php`.
   - Click "Submit" to proceed to `stream_selection.php`.
   - Select a stream from the dropdown to see the corresponding career scopes.

## Extra Notes

### How to Reset Form Data
- On `student_form.php`, the **Reset** button clears all form fields to their default empty state.
- To reset, simply click the "Reset" button before submitting the form.
- Note: Once the form is submitted and you navigate to `stream_selection.php`, you cannot reset the student details from there. To re-enter details, go back to `student_form.php` by clicking the browser's back button or re-entering the URL.

### How the Two Pages Are Connected
- **Data Flow**: The `student_form.php` uses the **POST** method to send the 15 fields of student data to `stream_selection.php`. The data is sent securely and is accessible in `stream_selection.php` using the `$_POST` superglobal array.
- **Form Submission**: The form in `student_form.php` has an `action` attribute set to `stream_selection.php`, which directs the submitted data to the second page.
- **Stream Selection**: On `stream_selection.php`, the submitted student details are displayed, and a new form allows the user to select a stream. The career scopes are dynamically generated based on the selected stream using PHP conditional logic.

### Beginner Tips
1. **Common Error: Apache Not Starting**:
   - **Cause**: Port conflicts (e.g., another program using port 80).
   - **Fix**:
     - Open XAMPP Control Panel, click "Config" next to Apache, and select `httpd.conf`.
     - Find `Listen 80` and change it to `Listen 8080`.
     - Save the file and restart Apache.
     - Access the project at `http://localhost:8080/student_project/student_form.php`.
   - Alternatively, check for programs using port 80 (e.g., Skype) and close them.
2. **File Not Found Error**:
   - Ensure the project folder is correctly placed in `C:\xampp\htdocs`.
   - Check the URL: It should be `http://localhost/student_project/student_form.php` (case-sensitive).
3. **PHP Not Working**:
   - Verify that Apache is running in the XAMPP Control Panel.
   - Ensure the file extension is `.php` and not `.html`.
4. **Editing Code**:
   - Use a text editor like VS Code for better syntax highlighting.
   - Save changes and refresh the browser to see updates.