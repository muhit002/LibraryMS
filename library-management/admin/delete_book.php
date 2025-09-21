<?php
session_start();
require_once '../config/db_connection.php';

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); 
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];
    
    // --- NEW: SAFETY CHECK ---
    // First, check if the book has any borrowing history.
    $check_sql = "SELECT COUNT(*) as count FROM borrowed_books WHERE book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result()->fetch_assoc();
    $check_stmt->close();

    // If the count is greater than 0, the book has been borrowed and cannot be deleted.
    if ($result['count'] > 0) {
        $_SESSION['message'] = "Cannot delete this book because it has a borrowing history. Consider archiving it instead.";
        $_SESSION['message_type'] = "danger";
        header("location: books.php");
        exit;
    }
    // --- END OF SAFETY CHECK ---


    // --- If the script reaches here, it's safe to delete. ---

    // 1. Get the image filename from the database to delete the file.
    $stmt_get = $conn->prepare("SELECT cover_image FROM books WHERE book_id = ?");
    $stmt_get->bind_param("i", $book_id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    if ($book = $result->fetch_assoc()) {
        $image_to_delete = $book['cover_image'];
        // 2. If a filename exists, delete the file from the server.
        if (!empty($image_to_delete)) {
            $file_path = '../uploads/book_covers/' . $image_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path); // This function deletes the file
            }
        }
    }
    $stmt_get->close();

    // 3. Now, delete the book record from the database.
    $sql = "DELETE FROM books WHERE book_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $book_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Book and its cover image deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            // This part is now less likely to be reached due to the check above, but is good for catching other errors.
            $_SESSION['message'] = "Error: Could not delete the book.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
}
$conn->close();
header("location: books.php");
exit;