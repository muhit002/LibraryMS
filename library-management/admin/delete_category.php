<?php
session_start();
require_once '../config/db_connection.php';

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$category_id = (int)$_GET['id'];

// IMPORTANT: Check if any books are currently using this category
$check_sql = "SELECT book_id FROM books WHERE category_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $category_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // If the category is in use, prevent deletion and show an error
    $_SESSION['message'] = "Cannot delete this category because it is associated with books. Please reassign the books to another category first.";
    $_SESSION['message_type'] = "danger";
} else {
    // If no books are using it, proceed with deletion
    $delete_sql = "DELETE FROM categories WHERE category_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $category_id);

    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not delete the category.";
        $_SESSION['message_type'] = "danger";
    }
    $delete_stmt->close();
}

$check_stmt->close();
$conn->close();

header('Location: categories.php');
exit;
?>