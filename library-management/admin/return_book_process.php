<?php
session_start();
require_once '../config/db_connection.php';

// Security check: ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if the borrow ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
    header('Location: issued_books.php');
    exit;
}

$borrow_id = (int)$_GET['id'];
$return_date = date('Y-m-d'); // Today's date

// --- Start Transaction for Data Integrity ---
$conn->begin_transaction();

try {
    // 1. Get the details of the borrowed book
    $sql_get_borrow = "SELECT book_id, member_id, due_date FROM borrowed_books WHERE borrow_id = ? AND status = 'Borrowed'";
    $stmt_get = $conn->prepare($sql_get_borrow);
    $stmt_get->bind_param("i", $borrow_id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Book has already been returned or borrow record not found.");
    }
    $borrow_details = $result->fetch_assoc();
    $book_id = $borrow_details['book_id'];
    $member_id = $borrow_details['member_id'];
    $due_date = $borrow_details['due_date'];
    $stmt_get->close();

    // 2. Update the borrowed_books table
    $sql_update_borrow = "UPDATE borrowed_books SET status = 'Returned', return_date = ? WHERE borrow_id = ?";
    $stmt_update = $conn->prepare($sql_update_borrow);
    $stmt_update->bind_param("si", $return_date, $borrow_id);
    $stmt_update->execute();
    $stmt_update->close();

    // 3. Increment the available_copies count for the book
    $sql_update_book = "UPDATE books SET available_copies = available_copies + 1 WHERE book_id = ?";
    $stmt_book = $conn->prepare($sql_update_book);
    $stmt_book->bind_param("i", $book_id);
    $stmt_book->execute();
    $stmt_book->close();

    // 4. --- PENALTY CALCULATION LOGIC ---
    $due_timestamp = strtotime($due_date);
    $return_timestamp = strtotime($return_date);

    if ($return_timestamp > $due_timestamp) {
        // The book is overdue, so calculate the fine
        $days_overdue = floor(($return_timestamp - $due_timestamp) / (60 * 60 * 24));
        
        // Define your penalty rate here (e.g., 0.50 per day)
        $penalty_per_day = 0.50;
        $fine_amount = $days_overdue * $penalty_per_day;

        // Insert a record into the new 'fines' table
        $sql_insert_fine = "INSERT INTO fines (borrow_id, member_id, fine_amount, fine_date_issued) VALUES (?, ?, ?, ?)";
        $stmt_fine = $conn->prepare($sql_insert_fine);
        $stmt_fine->bind_param("iids", $borrow_id, $member_id, $fine_amount, $return_date);
        $stmt_fine->execute();
        $stmt_fine->close();
        
        $_SESSION['message'] = "Book returned successfully. A fine of $" . number_format($fine_amount, 2) . " has been issued for being {$days_overdue} day(s) overdue.";
        $_SESSION['message_type'] = "warning"; // Use warning to highlight the fine
    } else {
        // Book returned on time
        $_SESSION['message'] = "Book returned successfully!";
        $_SESSION['message_type'] = "success";
    }

    // If everything worked, commit the changes
    $conn->commit();

} catch (Exception $e) {
    // If anything failed, roll back all database changes
    $conn->rollback();
    $_SESSION['message'] = "An error occurred: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

$conn->close();
header('Location: issued_books.php');
exit;
?>