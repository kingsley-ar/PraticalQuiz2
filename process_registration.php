<?php
// Start session
session_start();

// Include config file
require_once 'config.php';

// Initialize variables
$errors = [];
$input = $_POST;

// Validate and process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Email
    $input['email'] = trim($_POST['email']);
    if (empty($input['email'])) {
        $errors['email'] = "Please enter an email.";
    } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email.";
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM clients WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $input['email'];
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $errors['email'] = "This email is already registered.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate Password
    if (empty(trim($_POST['password']))) {
        $errors['password'] = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $errors['password'] = "Password must have at least 6 characters.";
    }
    
    // Validate First Name
    $input['first_name'] = trim($_POST['first_name']);
    if (empty($input['first_name'])) {
        $errors['first_name'] = "Please enter your first name.";
    }
    
    // Validate Last Name
    $input['last_name'] = trim($_POST['last_name']);
    if (empty($input['last_name'])) {
        $errors['last_name'] = "Please enter your last name.";
    }
    
    // Validate Gender
    $input['gender'] = $_POST['gender'] ?? '';
    if (empty($input['gender'])) {
        $errors['gender'] = "Please select your gender.";
    }
    
    // Validate Country
    $input['country'] = $_POST['country'] ?? '';
    if (empty($input['country'])) {
        $errors['country'] = "Please select your country.";
    }
    
    // Validate Terms
    if (!isset($_POST['terms'])) {
        $errors['terms'] = "You must agree to the terms and conditions.";
    } else {
        $input['terms'] = 'on';
    }
    
    // Newsletter (optional)
    $input['newsletter'] = isset($_POST['newsletter']) ? 'on' : 'off';
    
    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO clients (first_name, last_name, email, password, gender, country, newsletter_subscription, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $param_first_name,
                $param_last_name,
                $param_email,
                $param_password,
                $param_gender,
                $param_country,
                $param_newsletter
            );
            
            // Set parameters
            $param_first_name = $input['first_name'];
            $param_last_name = $input['last_name'];
            $param_email = $input['email'];
            $param_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Creates a password hash
            $param_gender = $input['gender'];
            $param_country = $input['country'];
            $param_newsletter = $input['newsletter'];
            
            // Attempt to execute
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: registration_success.php");
                exit();
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Store errors and input in session
    $_SESSION['errors'] = $errors;
    $_SESSION['input'] = $input;
    
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>