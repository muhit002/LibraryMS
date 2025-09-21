<?php
require_once '../includes/header.php';
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all members for the member dropdown
$members_sql = "SELECT member_id, name FROM members ORDER BY name ASC";
$members_result = $conn->query($members_sql);

// Fetch all AVAILABLE books for the book dropdown
$books_sql = "SELECT book_id, title FROM books WHERE available_copies > 0 ORDER BY title ASC";
$books_result = $conn->query($books_sql);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Issue a New Book</h1>
                    <p class="card-subtitle text-muted">Select a member and an available book to issue.</p>
                </div>
                <div class="card-body">
                    <form action="issue_book_process.php" method="POST">
                        <div class="mb-3">
                            <label for="member_id" class="form-label">Select Member</label>
                            <select class="form-select" id="member_id" name="member_id" required>
                                <option value="">-- Choose a Member --</option>
                                <?php
                                if ($members_result->num_rows > 0) {
                                    while($row = $members_result->fetch_assoc()) {
                                        echo '<option value="' . $row['member_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Select Book (Only available books are shown)</label>
                            <select class="form-select" id="book_id" name="book_id" required>
                                <option value="">-- Choose a Book --</option>
                                 <?php
                                if ($books_result->num_rows > 0) {
                                    while($row = $books_result->fetch_assoc()) {
                                        echo '<option value="' . $row['book_id'] . '">' . htmlspecialchars($row['title']) . '</option>';
                                    }
                                } else {
                                     echo '<option value="" disabled>No books are currently available.</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- NEW: Manual Due Date Input -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                   value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>                        </div>
                        <!-- END NEW -->

                        <div class="d-flex justify-content-end">
                            <a href="dashboard.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-info text-white">Issue Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>