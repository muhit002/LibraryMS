<?php
session_start();
require_once 'config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT member_id, name, email, password FROM members WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $name, $email, $hashed_password);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start session
                        $_SESSION["member_logged_in"] = true;
                        $_SESSION["member_id"] = $id;
                        $_SESSION["member_name"] = $name;
                        
                        header("location: member_dashboard.php");
                        exit;
                    } else {
                        $_SESSION['message'] = "The password you entered was not valid.";
                    }
                }
            } else {
                $_SESSION['message'] = "No account found with that email.";
            }
        } else {
            $_SESSION['message'] = "Oops! Something went wrong.";
        }
        $stmt->close();
    }
    $conn->close();
    header("location: login.php");
    exit;
}
?>