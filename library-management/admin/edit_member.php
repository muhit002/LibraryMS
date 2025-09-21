<?php
require_once '../includes/header.php';
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: members.php');
    exit;
}

$member_id = $_GET['id'];

// Fetch member details
$stmt = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header('Location: members.php');
    exit;
}
$member = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Edit Member</h1>
                    <p class="card-subtitle text-muted">Update details for <?php echo htmlspecialchars($member['name']); ?></p>
                </div>
                <div class="card-body">
                    <form action="edit_member_process.php" method="POST">
                        <input type="hidden" name="member_id" value="<?php echo $member['member_id']; ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (Optional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="members.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success">Update Member</button>
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