<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Get the current date for registration_date
    $registration_date = date('Y-m-d');

    // Prepare an insert statement
    $sql = "INSERT INTO members (name, email, phone, password, registration_date) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $registration_date);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Member added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            // Check for duplicate email
            if ($conn->errno == 1062) {
                 $_SESSION['message'] = "Error: A member with this email already exists.";
            } else {
                 $_SESSION['message'] = "Error: Could not add the member.";
            }
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
    header("location: members.php");
    exit;
}
$conn->close();
?>