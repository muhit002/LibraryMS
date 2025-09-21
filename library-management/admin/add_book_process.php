<?php
session_start();
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category_id = $_POST['category_id'];
    $total_copies = $_POST['total_copies'];
    $available_copies = $total_copies;
    $cover_image_name = null;

    // --- Enhanced Image Upload Logic with Debugging ---
    $target_dir = "../uploads/book_covers/";

    // Check if the target directory exists, if not, create it
    if (!is_dir($target_dir)) {
        // The third parameter 'true' allows the creation of nested directories
        mkdir($target_dir, 0755, true);
    }

    // Check if a file was actually uploaded without errors
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        
        $image_name = basename($_FILES["cover_image"]["name"]);
        // Sanitize the filename to make it safe
        $unique_image_name = time() . '_' . uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $image_name);
        $target_file = $target_dir . $unique_image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['message'] = "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
            $_SESSION['message_type'] = "danger";
            header("location: add_book.php");
            exit;
        }

        // Attempt to move the uploaded file to its new home
        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
            $cover_image_name = $unique_image_name; // Success! Save the filename for the database.
        } else {
            // This is the most likely error if permissions are wrong
            $_SESSION['message'] = "CRITICAL ERROR: Could not move the uploaded file. Please check the server's folder permissions for the 'uploads' directory.";
            $_SESSION['message_type'] = "danger";
            header("location: add_book.php");
            exit;
        }
    }
    // --- End Image Upload Logic ---

    // Now, insert the book data (with or without an image name) into the database
    $sql = "INSERT INTO books (title, author, isbn, category_id, total_copies, available_copies, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiiis", $title, $author, $isbn, $category_id, $total_copies, $available_copies, $cover_image_name);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Book added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not add the book to the database.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
    header("location: books.php");
    exit;
}
?>