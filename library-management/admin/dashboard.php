<?php
// Include the header file, which also starts the session
require_once '../includes/header.php';

// --- Security Check ---
// If the admin is not logged in, redirect them to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require_once '../config/db_connection.php';

// --- Fetch Statistics from the Database ---

// 1. Total Books
$total_books_query = "SELECT SUM(total_copies) as total FROM books";
$total_books_result = $conn->query($total_books_query);
$total_books = $total_books_result->fetch_assoc()['total'] ?? 0;

// 2. Total Members
$total_members_query = "SELECT COUNT(member_id) as total FROM members";
$total_members_result = $conn->query($total_members_query);
$total_members = $total_members_result->fetch_assoc()['total'] ?? 0;

// 3. Books Currently Issued
$issued_books_query = "SELECT COUNT(borrow_id) as total FROM borrowed_books WHERE status = 'Borrowed'";
$issued_books_result = $conn->query($issued_books_query);
$issued_books = $issued_books_result->fetch_assoc()['total'] ?? 0;

// 4. Books Overdue
$overdue_books_query = "SELECT COUNT(borrow_id) as total FROM borrowed_books WHERE status = 'Borrowed' AND due_date < CURDATE()";
$overdue_books_result = $conn->query($overdue_books_query);
$overdue_books = $overdue_books_result->fetch_assoc()['total'] ?? 0;

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Admin Dashboard</h1>
            <p class="lead">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="row">
        <!-- Total Books Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fs-2"><?php echo $total_books; ?></h5>
                            <p class="card-text">Total Books</p>
                        </div>
                        <i class="bi bi-journal-bookmark-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Members Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fs-2"><?php echo $total_members; ?></h5>
                            <p class="card-text">Total Members</p>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Books Issued Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fs-2"><?php echo $issued_books; ?></h5>
                            <p class="card-text">Books Issued</p>
                        </div>
                        <i class="bi bi-box-arrow-up-right" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Overdue Books Card -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fs-2"><?php echo $overdue_books; ?></h5>
                            <p class="card-text">Overdue Books</p>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <!-- Corrected Links -->
                    <a href="add_book.php" class="btn btn-primary me-2"><i class="bi bi-book"></i> Add New Book</a>
                    <a href="add_member.php" class="btn btn-success me-2"><i class="bi bi-person-plus"></i> Add New Member</a>
                    <a href="issue_book.php" class="btn btn-info text-white"><i class="bi bi-journal-arrow-up"></i> Issue a Book</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer
require_once '../includes/footer.php';
?>