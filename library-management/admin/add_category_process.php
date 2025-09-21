<?php
session_start();
require_once '../config/db_connection.php';

// Security check: ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['message_type'] = "danger";
        header('Location: categories.php');
        exit;
    }

    // Check for duplicate category names to prevent errors
    $check_sql = "SELECT category_id FROM categories WHERE category_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $category_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "A category with this name already exists.";
        $_SESSION['message_type'] = "warning";
    } else {
        // Insert the new category using a prepared statement
        $insert_sql = "INSERT INTO categories (category_name) VALUES (?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("s", $category_name);
        
        if ($insert_stmt->execute()) {
            $_SESSION['message'] = "Category added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not add the category.";
            $_SESSION['message_type'] = "danger";
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
    $conn->close();
}

// Redirect back to the main category page
header('Location: categories.php');
exit;
?>