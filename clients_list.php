<?php
require_once 'config.php';

// Fetch all clients from database
$sql = "SELECT id, first_name, last_name, email, gender, country, registration_date FROM clients ORDER BY registration_date DESC";
$result = mysqli_query($conn, $sql);

// Check for messages
$success_msg = $_SESSION['success_message'] ?? '';
$error_msg = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 6px 12px;
            text-decoration: none;
            margin-right: 5px;
            border-radius: 3px;
        }
        .view-btn { background-color: #2196F3; color: white; }
        .edit-btn { background-color: #FFC107; color: black; }
        .delete-btn { background-color: #f44336; color: white; }
        .add-new {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registered Clients</h1>
        
        <?php if ($success_msg): ?>
            <div class="message success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="message error"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <a href="<?php echo BASE_URL; ?>index.php" class="add-new">Register New Client</a>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Country</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['country']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['registration_date'])); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>view_client.php?id=<?php echo $row['id']; ?>" class="action-btn view-btn">View</a>
                                <a href="<?php echo BASE_URL; ?>edit_client.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">Edit</a>
                                <a href="<?php echo BASE_URL; ?>delete_client.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No clients found.</p>
        <?php endif; ?>
    </div>
</body>
</html>