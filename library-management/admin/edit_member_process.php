<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check if a new password was provided
    if (!empty($password)) {
        // A new password was entered, so we need to update it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE members SET name = ?, email = ?, phone = ?, password = ? WHERE member_id = ?";
        $param_types = "ssssi";
        $params = [&$name, &$email, &$phone, &$hashed_password, &$member_id];
    } else {
        // No new password, so we don't update that field
        $sql = "UPDATE members SET name = ?, email = ?, phone = ? WHERE member_id = ?";
        $param_types = "sssi";
        $params = [&$name, &$email, &$phone, &$member_id];
    }
    
    if ($stmt = $conn->prepare($sql)) {
        // Use call_user_func_array to bind parameters dynamically
        call_user_func_array([$stmt, 'bind_param'], array_merge([$param_types], $params));
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Member updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating member.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
    header("location: members.php");
    exit;
}
$conn->close();
?>