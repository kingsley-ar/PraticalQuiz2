<?php
/**
 * Database configuration for XAMPP on port 8080
 */
 
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Default XAMPP has no password
define('DB_NAME', 'client_registration_system');
define('DB_PORT', 3306); // Default MySQL port

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Set base URL for port 8080
define('BASE_URL', 'http://localhost:8080/registration_system/');
?>