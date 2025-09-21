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
    $member_id = $_POST['member_id'];
    
    // UPDATED: Get the due date directly from the form submission
    $due_date = $_POST['due_date']; 
    
    // The issue date is still today's date
    $issue_date = date('Y-m-d');

    // Start Database Transaction
    $conn->begin_transaction();

    try {
        // 1. Insert into the borrowed_books table
        $sql1 = "INSERT INTO borrowed_books (book_id, member_id, issue_date, due_date, status) VALUES (?, ?, ?, ?, 'Borrowed')";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("iiss", $book_id, $member_id, $issue_date, $due_date);
        $stmt1->execute();
        
        // 2. Decrement the available_copies count in the books table
        $sql2 = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ? AND available_copies > 0";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $book_id);
        $stmt2->execute();

        // If both queries were successful, commit the transaction
        $conn->commit();
        
        $_SESSION['message'] = "Book issued successfully!";
        $_SESSION['message_type'] = "success";

    } catch (mysqli_sql_exception $exception) {
        // If anything went wrong, roll back the transaction
        $conn->rollback();
        
        $_SESSION['message'] = "Error issuing book. Please try again.";
        $_SESSION['message_type'] = "danger";
    } finally {
        if (isset($stmt1)) $stmt1->close();
        if (isset($stmt2)) $stmt2->close();
        $conn->close();
    }

    header("location: issued_books.php");
    exit;
}
?>