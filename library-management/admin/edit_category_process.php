<?php
session_start();
require_once '../config/db_connection.php';

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = (int)$_POST['category_id'];
    $category_name = trim($_POST['category_name']);

    if (empty($category_name) || empty($category_id)) {
        $_SESSION['message'] = "Invalid data submitted.";
        $_SESSION['message_type'] = "danger";
        header('Location: categories.php');
        exit;
    }

    // Check if another category already has the new name
    $check_sql = "SELECT category_id FROM categories WHERE category_name = ? AND category_id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $category_name, $category_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "Another category with this name already exists.";
        $_SESSION['message_type'] = "warning";
    } else {
        // Update the category name
        $update_sql = "UPDATE categories SET category_name = ? WHERE category_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $category_name, $category_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Category updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not update the category.";
            $_SESSION['message_type'] = "danger";
        }
        $update_stmt->close();
    }
    $check_stmt->close();
    $conn->close();
}

header('Location: categories.php');
exit;
?>