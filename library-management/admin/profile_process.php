<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_id = $_SESSION['admin_id'];

    // Validate new password
    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = "New passwords do not match.";
        $_SESSION['message_type'] = "danger";
        header("location: profile.php");
        exit;
    }

    // Get current password from DB
    $sql = "SELECT password FROM admins WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $hashed_password = $admin['password'];
    $stmt->close();

    // Verify current password
    if (password_verify($current_password, $hashed_password)) {
        // Hash the new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the password in the database
        $update_sql = "UPDATE admins SET password = ? WHERE admin_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_hashed_password, $admin_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Password updated successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating password.";
            $_SESSION['message_type'] = "danger";
        }
        $update_stmt->close();
    } else {
        $_SESSION['message'] = "Incorrect current password.";
        $_SESSION['message_type'] = "danger";
    }
    
    $conn->close();
    header("location: profile.php");
    exit;
}