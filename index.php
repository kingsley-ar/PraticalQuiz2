<?php
// Start session for error messages
session_start();

// Include config file
require_once 'config.php';

// Initialize variables with session data if available
$input = $_SESSION['input'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['input']);
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration Form</title>
    <style>
        /* Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .checkbox-group {
            margin: 20px 0;
        }
        
        .checkbox-group label {
            display: inline;
            font-weight: normal;
            margin-left: 8px;
        }
        
        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        
        .gender-option {
            display: flex;
            align-items: center;
        }
        
        .gender-option input {
            margin-right: 8px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 14px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Error Messages */
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 6px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .container {
                padding: 20px;
            }
            
            .gender-options {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Complete Registration Form</h1>
        
        <?php if (!empty($errors) && is_array($errors)): ?>
            <div class="error-message" style="color: #e74c3c; background-color: #fdecea; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
                <h3 style="margin-bottom: 10px;">Please fix these errors:</h3>
                <ul style="margin-left: 20px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo is_array($err) ? implode(' ', $err) : $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="process_registration.php" method="post">
            <!-- Personal Information -->
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name*</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($input['first_name'] ?? ''); ?>" required>
                    <?php if (isset($errors['first_name'])): ?>
                        <span class="error"><?php echo $errors['first_name']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name*</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($input['last_name'] ?? ''); ?>" required>
                    <?php if (isset($errors['last_name'])): ?>
                        <span class="error"><?php echo $errors['last_name']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <span class="error"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone*</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($input['phone'] ?? ''); ?>" required>
                    <?php if (isset($errors['phone'])): ?>
                        <span class="error"><?php echo $errors['phone']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth*</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($input['date_of_birth'] ?? ''); ?>" required>
                    <?php if (isset($errors['date_of_birth'])): ?>
                        <span class="error"><?php echo $errors['date_of_birth']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Gender*</label>
                    <div class="gender-options">
                        <div class="gender-option">
                            <input type="radio" id="male" name="gender" value="Male" <?php echo (isset($input['gender']) && $input['gender'] == 'Male' ? 'checked' : ''); ?> required>
                            <label for="male">Male</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" id="female" name="gender" value="Female" <?php echo (isset($input['gender']) && $input['gender'] == 'Female' ? 'checked' : ''); ?>>
                            <label for="female">Female</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" id="other" name="gender" value="Other" <?php echo (isset($input['gender']) && $input['gender'] == 'Other' ? 'checked' : ''); ?>>
                            <label for="other">Other</label>
                        </div>
                    </div>
                    <?php if (isset($errors['gender'])): ?>
                        <span class="error"><?php echo $errors['gender']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Address Information -->
            <div class="form-group">
                <label for="address">Address*</label>
                <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($input['address'] ?? ''); ?></textarea>
                <?php if (isset($errors['address'])): ?>
                    <span class="error"><?php echo $errors['address']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City*</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($input['city'] ?? ''); ?>" required>
                    <?php if (isset($errors['city'])): ?>
                        <span class="error"><?php echo $errors['city']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="state">State/Province*</label>
                    <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($input['state'] ?? ''); ?>" required>
                    <?php if (isset($errors['state'])): ?>
                        <span class="error"><?php echo $errors['state']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="postal_code">Postal Code*</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($input['postal_code'] ?? ''); ?>" required>
                    <?php if (isset($errors['postal_code'])): ?>
                        <span class="error"><?php echo $errors['postal_code']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="country">Country*</label>
                    <select id="country" name="country" required>
                        <option value="">-- Select Country --</option>
                        <?php include 'countries.php'; ?>
                    </select>
                    <?php if (isset($errors['country'])): ?>
                        <span class="error"><?php echo $errors['country']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Password -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password*</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class="error"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password*</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <span class="error"><?php echo $errors['confirm_password']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Checkboxes -->
            <div class="checkbox-group">
                <input type="checkbox" id="terms" name="terms" <?php echo (!empty($input['terms'])) ? 'checked' : ''; ?> required>
                <label for="terms">I agree with terms and conditions*</label>
                <?php if (isset($errors['terms'])): ?>
                    <span class="error"><?php echo is_array($errors['terms']) ? implode(' ', $errors['terms']) : $errors['terms']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="newsletter" name="newsletter" <?php echo (!empty($input['newsletter'])) ? 'checked' : ''; ?>>
                <label for="newsletter">I want to receive the newsletter</label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit">Register</button>
        </form>
        
        <div class="footer">
            Arthur, John Kingsley...CY1A
        </div>
    </div>
    
    <script>
        // Set the selected country if returning with errors
        document.addEventListener('DOMContentLoaded', function() {
            var country = <?php echo json_encode($input['country'] ?? ''); ?>;
            if (country) {
                var countrySelect = document.getElementById('country');
                if (countrySelect) countrySelect.value = country;
            }
        });
    </script>
    </body>
</html>