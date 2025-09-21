<?php
// Include header and database connection
require_once '../includes/header.php';
require_once '../config/db_connection.php';

// Security Check: Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all members from the database
$sql = "SELECT * FROM members ORDER BY name ASC";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1>Manage Members</h1>
            <a href="add_member.php" class="btn btn-success"><i class="bi bi-person-plus"></i> Add New Member</a>
        </div>
    </div>

    <hr>

    <?php
    // Display success or error messages if they exist
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">';
        echo $_SESSION['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($member = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td><?php echo htmlspecialchars($member['phone']); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($member['registration_date'])); ?></td>
                                    <td>
                                        <a href="edit_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="delete_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this member?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No members found.</td>
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