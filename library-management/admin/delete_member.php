<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $member_id = $_GET['id'];
    
    // --- Start a Database Transaction ---
    // This ensures data integrity. Both deletions must succeed, or none will.
    $conn->begin_transaction();

    try {
        // Step 1: Delete all borrowing records associated with this member.
        // This removes the "child" records, satisfying the foreign key constraint.
        $sql_delete_history = "DELETE FROM borrowed_books WHERE member_id = ?";
        $stmt_history = $conn->prepare($sql_delete_history);
        $stmt_history->bind_param("i", $member_id);
        $stmt_history->execute();
        $stmt_history->close();

        // Step 2: Now it's safe to delete the member from the "parent" table.
        $sql_delete_member = "DELETE FROM members WHERE member_id = ?";
        $stmt_member = $conn->prepare($sql_delete_member);
        $stmt_member->bind_param("i", $member_id);
        $stmt_member->execute();
        
        // Check if the member was actually deleted
        if ($stmt_member->affected_rows > 0) {
            // If everything was successful, commit the changes to the database
            $conn->commit();
            $_SESSION['message'] = "Member and their borrowing history deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            // If the member wasn't found or couldn't be deleted for some reason
            throw new Exception("Member could not be found or deleted.");
        }
        $stmt_member->close();

    } catch (mysqli_sql_exception $exception) {
        // If any part of the transaction fails, roll everything back
        $conn->rollback();
        $_SESSION['message'] = "Error: Could not delete the member due to a database error. It might be because they have active borrows.";
        $_SESSION['message_type'] = "danger";
    }
}

$conn->close();
header("location: members.php");
exit;
?>