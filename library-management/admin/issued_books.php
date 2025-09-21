<?php
require_once '../includes/header.php';
require_once '../config/db_connection.php'; // This now includes our PENALTY_PER_DAY constant

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all currently borrowed books with member and book details
$sql = "SELECT bb.borrow_id, b.title, m.name as member_name, bb.issue_date, bb.due_date
        FROM borrowed_books bb
        JOIN books b ON bb.book_id = b.book_id
        JOIN members m ON bb.member_id = m.member_id
        WHERE bb.status = 'Borrowed'
        ORDER BY bb.due_date ASC";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1>Issued Books</h1>
            <a href="issue_book.php" class="btn btn-info text-white"><i class="bi bi-journal-arrow-up"></i> Issue Another Book</a>
        </div>
    </div>
    <hr>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">';
        echo $_SESSION['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['message'], $_SESSION['message_type']);
    }
    ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Book Title</th>
                            <th>Member Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <!-- NEW: Penalty Column -->
                            <th>Penalty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <?php
                                // --- NEW: Penalty Calculation Logic ---
                                $fine = 0;
                                $is_overdue = false;
                                $due_date = new DateTime($row['due_date']);
                                $current_date = new DateTime('now');

                                // Check if the current date is past the due date
                                if ($current_date > $due_date) {
                                    $is_overdue = true;
                                    $interval = $current_date->diff($due_date);
                                    $days_overdue = $interval->days;
                                    $fine = $days_overdue * PENALTY_PER_DAY;
                                }
                                // --- End Penalty Calculation Logic ---
                                ?>
                                <tr class="<?php echo $is_overdue ? 'table-danger' : ''; ?>">
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['issue_date'])); ?></td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($row['due_date'])); ?>
                                        <?php if ($is_overdue): ?>
                                            <span class="badge bg-danger">Overdue</span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- NEW: Display the calculated penalty -->
                                    <td>
                                        <span class="fw-bold <?php echo $is_overdue ? 'text-danger' : ''; ?>">
                                            ৳ <?php echo number_format($fine, 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="return_book_process.php?id=<?php echo $row['borrow_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this book as returned?');">
                                            Mark as Returned
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <!-- UPDATED: colspan is now 6 -->
                                <td colspan="6" class="text-center">No books are currently issued.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>