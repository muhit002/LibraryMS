<?php
// Always start the session
session_start();

// Include the database connection file
require_once '../config/db_connection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- Prepare a SELECT statement to prevent SQL Injection ---
    $sql = "SELECT admin_id, username, password FROM admins WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $username, $hashed_password);
                if ($stmt->fetch()) {
                    // Use password_verify() to check against the hashed password from the DB
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        
                        // Store data in session variables
                        $_SESSION["admin_logged_in"] = true;
                        $_SESSION["admin_id"] = $id;
                        $_SESSION["admin_username"] = $username;
                        
                        // Redirect user to the dashboard
                        header("location: dashboard.php");
                        exit;
                    } else {
                        // Password is not valid
                        $_SESSION['error_message'] = "The password you entered was not valid.";
                        header("location: login.php");
                        exit;
                    }
                }
            } else {
                // Username doesn't exist
                $_SESSION['error_message'] = "No account found with that username.";
                header("location: login.php");
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>