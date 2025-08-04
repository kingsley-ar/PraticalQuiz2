<?php
// Start session
session_start();

// Redirect if not coming from successful registration
if (!isset($_SESSION['success_message'])) {
    header("Location :" . BASE_URL . "index.php");
    exit();
}

$success_message = $_SESSION['success_message'];
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding: 50px;
        }
        .success-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            color: #4CAF50;
            font-size: 50px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2><?php echo $success_message; ?></h2>
        <p>Thank you for registering with us.</p>
        <div>
            <a href="<?php echo BASE_URL; ?>index.php" class="btn">Register Another</a>
            <a href="<?php echo BASE_URL; ?>clients_list.php" class="btn">View All Clients</a>
        </div>
    </div>
</body>
</html>