<?php
require_once 'config.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid client ID";
    header("Location: " . BASE_URL . "clients_list.php");
    exit();
}

$id = intval($_GET['id']);

// Initialize variables
$errors = [];
$client = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs (similar to registration)
    $client['first_name'] = trim($_POST['first_name']);
    if (empty($client['first_name'])) {
        $errors['first_name'] = "Please enter first name.";
    }
    
    $client['last_name'] = trim($_POST['last_name']);
    if (empty($client['last_name'])) {
        $errors['last_name'] = "Please enter last name.";
    }
    
    $client['email'] = trim($_POST['email']);
    if (empty($client['email'])) {
        $errors['email'] = "Please enter email.";
    } elseif (!filter_var($client['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email.";
    } else {
        // Check if email exists for other clients
        $sql = "SELECT id FROM clients WHERE email = ? AND id != ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_email, $param_id);
            $param_email = $client['email'];
            $param_id = $id;
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $errors['email'] = "This email is already registered.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    $client['gender'] = $_POST['gender'] ?? '';
    if (empty($client['gender'])) {
        $errors['gender'] = "Please select gender.";
    }
    
    $client['country'] = $_POST['country'] ?? '';
    if (empty($client['country'])) {
        $errors['country'] = "Please select country.";
    }
    
    $client['newsletter'] = isset($_POST['newsletter']) ? 'on' : 'off';
    
    // Update if no errors
    if (empty($errors)) {
        $sql = "UPDATE clients SET first_name=?, last_name=?, email=?, gender=?, country=?, newsletter_subscription=? WHERE id=?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssi",
                $param_first_name,
                $param_last_name,
                $param_email,
                $param_gender,
                $param_country,
                $param_newsletter,
                $param_id
            );
            
            $param_first_name = $client['first_name'];
            $param_last_name = $client['last_name'];
            $param_email = $client['email'];
            $param_gender = $client['gender'];
            $param_country = $client['country'];
            $param_newsletter = $client['newsletter'];
            $param_id = $id;
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Client updated successfully";
                header("Location: " . BASE_URL . "view_client.php?id=" . $id);
                exit();
            } else {
                $errors[] = "Database error. Please try again.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
} else {
    // Fetch existing client data
    $sql = "SELECT * FROM clients WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $id;
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $client = mysqli_fetch_assoc($result);
            
            if (!$client) {
                $_SESSION['error_message'] = "Client not found";
                header("Location: " . BASE_URL . "clients_list.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Database error";
            header("Location: " . BASE_URL . "clients_list.php");
            exit();
        }
        
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <style>
        /* Same styles as registration form */
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input[type="text"], input[type="email"], select {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;
        }
        .gender-options { display: flex; gap: 15px; margin-top: 10px; }
        .gender-option { display: flex; align-items: center; }
        .gender-option input { margin-right: 5px; }
        .checkbox-group { margin: 15px 0; }
        .checkbox-group label { display: inline; font-weight: normal; margin-left: 5px; }
        .error { color: #e74c3c; font-size: 14px; margin-top: 5px; }
        .btn { 
            background-color: #3498db; color: white; border: none; padding: 10px 20px; 
            font-size: 16px; border-radius: 4px; cursor: pointer; margin-right: 10px;
        }
        .btn-cancel { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Client Information</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message" style="color: #e74c3c; margin-bottom: 20px;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo BASE_URL; ?>edit_client.php?id=<?php echo $id; ?>" method="post">
            <!-- First Name -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($client['first_name']); ?>" required>
            </div>
            
            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($client['last_name']); ?>" required>
            </div>
            
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            
            <!-- Gender -->
            <div class="form-group">
                <label>Gender</label>
                <div class="gender-options">
                    <div class="gender-option">
                        <input type="radio" id="male" name="gender" value="Male" <?php echo ($client['gender'] == 'Male') ? 'checked' : ''; ?>>
                        <label for="male">Male</label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" id="female" name="gender" value="Female" <?php echo ($client['gender'] == 'Female') ? 'checked' : ''; ?>>
                        <label for="female">Female</label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" id="other" name="gender" value="Other" <?php echo ($client['gender'] == 'Other') ? 'checked' : ''; ?>>
                        <label for="other">Other</label>
                    </div>
                </div>
            </div>
            
            <!-- Country -->
            <div class="form-group">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                    <option value="">-- Select Country --</option>
                    <option value="US" <?php echo ($client['country'] == 'US') ? 'selected' : ''; ?>>United States</option>
                    <option value="UK" <?php echo ($client['country'] == 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                    <option value="CA" <?php echo ($client['country'] == 'CA') ? 'selected' : ''; ?>>Canada</option>
                    <option value="AU" <?php echo ($client['country'] == 'AU') ? 'selected' : ''; ?>>Australia</option>
                    <option value="IN" <?php echo ($client['country'] == 'IN') ? 'selected' : ''; ?>>India</option>
                </select>
            </div>
            
            <!-- Newsletter -->
            <div class="checkbox-group">
                <input type="checkbox" id="newsletter" name="newsletter" <?php echo ($client['newsletter_subscription'] == 'on') ? 'checked' : ''; ?>>
                <label for="newsletter">Subscribe to newsletter</label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn">Update Client</button>
            <a href="<?php echo BASE_URL; ?>view_client.php?id=<?php echo $id; ?>" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>