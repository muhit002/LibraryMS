<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category_id = $_POST['category_id'];
    $new_total_copies = $_POST['total_copies'];

    // --- Complex logic to adjust available_copies ---
    // First, get the old total_copies to see how many were added or removed
    $stmt_old = $conn->prepare("SELECT total_copies, available_copies FROM books WHERE book_id = ?");
    $stmt_old->bind_param("i", $book_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result()->fetch_assoc();
    $old_total_copies = $result_old['total_copies'];
    $current_available = $result_old['available_copies'];

    // Calculate the difference
    $difference = $new_total_copies - $old_total_copies;
    $new_available_copies = $current_available + $difference;
    
    // Ensure available copies doesn't go below zero
    if ($new_available_copies < 0) {
        $new_available_copies = 0;
    }

    $stmt_old->close();
    // --- End of complex logic ---

    // Prepare an update statement
    $sql = "UPDATE books SET title = ?, author = ?, isbn = ?, category_id = ?, total_copies = ?, available_copies = ? WHERE book_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiiii", $title, $author, $isbn, $category_id, $new_total_copies, $new_available_copies, $book_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Book updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating book.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
    header("location: books.php");
    exit;
}
$conn->close();
?>