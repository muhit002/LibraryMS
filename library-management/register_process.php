<?php
session_start();
require_once 'config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // --- Server-side Validations ---
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        header("location: register.php");
        exit;
    }

    // Check if email already exists
    $sql_check = "SELECT member_id FROM members WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        $_SESSION['message'] = "An account with this email already exists.";
        $stmt_check->close();
        header("location: register.php");
        exit;
    }
    $stmt_check->close();

    // --- Proceed with Registration ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $registration_date = date('Y-m-d');

    $sql = "INSERT INTO members (name, email, phone, password, registration_date) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $registration_date);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Registration successful! You can now log in.";
            $_SESSION['message_type'] = "success";
            header("location: login.php");
        } else {
            $_SESSION['message'] = "Something went wrong. Please try again.";
            header("location: register.php");
        }
        $stmt->close();
    }
}
$conn->close();
?>