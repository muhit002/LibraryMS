<?php
// Ensure the config file with the PENALTY constant is included
require_once 'includes/public_header.php'; 
require_once 'config/db_connection.php'; // This should define PENALTY_PER_DAY

// Security Check
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$member_id = $_SESSION['member_id'];
$search_term = '';

// --- SEARCH FUNCTIONALITY (from your code) ---
$sql = "SELECT b.title, b.cover_image, bb.issue_date, bb.due_date
        FROM borrowed_books bb
        JOIN books b ON bb.book_id = b.book_id
        WHERE bb.member_id = ? AND bb.status = 'Borrowed'";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = trim($_GET['search']);
    $sql .= " AND b.title LIKE ?";
}

$sql .= " ORDER BY bb.due_date ASC";
$stmt = $conn->prepare($sql);

if (!empty($search_term)) {
    $search_param = "%" . $search_term . "%";
    $stmt->bind_param("is", $member_id, $search_param);
} else {
    $stmt->bind_param("i", $member_id);
}

$stmt->execute();
$result = $stmt->get_result();
$base_url = "http://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>My Dashboard</h1>
            <p class="lead">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['member_name']); ?></strong>!</p>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>My Currently Borrowed Books</h4>
                     <!-- --- SEARCH FORM (from your code) --- -->
                    <form action="" method="GET" class="d-flex" style="width: 300px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search my books..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                        <?php if (!empty($search_term)): ?>
                            <a href="member_dashboard.php" class="btn btn-secondary ms-1">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">Cover</th>
                                    <th>Book Title</th>
                                    <th>Date Issued</th>
                                    <th>Date Due</th>
                                    <!-- NEW: Header for the penalty column -->
                                    <th>Penalty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <?php
                                        // --- MERGED: Penalty Calculation Logic ---
                                        $fine = 0;
                                        $is_overdue = false;
                                        $due_date = new DateTime($row['due_date']);
                                        $current_date = new DateTime('now');
                                        if ($current_date > $due_date) {
                                            $is_overdue = true;
                                            $interval = $current_date->diff($due_date);
                                            $days_overdue = $interval->days;
                                            $fine = $days_overdue * (defined('PENALTY_PER_DAY') ? PENALTY_PER_DAY : 0); // Safely get penalty
                                        }
                                        // --- End Penalty Logic ---
                                        ?>
                                        <tr class="<?php echo $is_overdue ? 'table-danger' : ''; ?>">
                                            <td>
                                                <img src="<?php echo !empty($row['cover_image']) ? $base_url . 'uploads/book_covers/' . htmlspecialchars($row['cover_image']) : 'https://placehold.co/60x80?text=N/A'; ?>"
                                                     alt="Cover for <?php echo htmlspecialchars($row['title']); ?>"
                                                     width="60" class="img-thumbnail">
                                            </td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($row['issue_date'])); ?></td>
                                            <td>
                                                <?php echo date('F j, Y', strtotime($row['due_date'])); ?>
                                                <?php if ($is_overdue): ?>
                                                    <span class="badge bg-danger ms-2">Overdue</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- NEW: Table cell to display the penalty -->
                                            <td>
                                                <span class="fw-bold <?php echo $is_overdue ? 'text-danger' : ''; ?>">
                                                    ৳ <?php echo number_format($fine, 2); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <!-- UPDATED: colspan is now 5 -->
                                        <td colspan="5" class="text-center">
                                            <?php echo !empty($search_term) ? 'No borrowed books found matching your search.' : 'You have no books currently borrowed.'; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'includes/public_footer.php';
?>