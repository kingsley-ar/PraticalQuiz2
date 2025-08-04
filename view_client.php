<?php
require_once 'config.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid client ID";
    header("Location: " . BASE_URL . "clients_list.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch client data
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
} else {
    $_SESSION['error_message'] = "Database error";
    header("Location: " . BASE_URL . "clients_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .client-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .detail-group {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            padding: 8px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .action-btns {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            margin: 0 5px;
            border-radius: 4px;
        }
        .back-btn {
            background-color: #6c757d;
            color: white;
        }
        .edit-btn {
            background-color: #FFC107;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Client Details</h1>
        
        <div class="client-details">
            <div class="detail-group">
                <div class="detail-label">Client ID</div>
                <div class="detail-value"><?php echo $client['id']; ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Registration Date</div>
                <div class="detail-value"><?php echo date('M d, Y', strtotime($client['registration_date'])); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">First Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($client['first_name']); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Last Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($client['last_name']); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Email</div>
                <div class="detail-value"><?php echo htmlspecialchars($client['email']); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Gender</div>
                <div class="detail-value"><?php echo htmlspecialchars($client['gender']); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Country</div>
                <div class="detail-value"><?php echo htmlspecialchars($client['country']); ?></div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Newsletter Subscription</div>
                <div class="detail-value"><?php echo ($client['newsletter_subscription'] == 'on') ? 'Yes' : 'No'; ?></div>
            </div>
        </div>
        
        <div class="action-btns">
            <a href="<?php echo BASE_URL; ?>clients_list.php" class="btn back-btn">Back to List</a>
            <a href="<?php echo BASE_URL; ?>edit_client.php?id=<?php echo $client['id']; ?>" class="btn edit-btn">Edit Client</a>
        </div>
    </div>
</body>
</html>