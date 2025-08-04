<?php
require_once 'config.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid client ID";
    header("Location: " . BASE_URL . "clients_list.php");
    exit();
}

$id = intval($_GET['id']);

// Prepare delete statement
$sql = "DELETE FROM clients WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $param_id);
    $param_id = $id;
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Client deleted successfully";
    } else {
        $_SESSION['error_message'] = "Error deleting client";
    }
    
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error_message'] = "Database error";
}

mysqli_close($conn);

header("Location: " . BASE_URL . "clients_list.php");
exit();
?>