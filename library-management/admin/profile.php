<?php
require_once '../includes/header.php';
// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Admin Profile Settings</h1>
                </div>
                <div class="card-body">
                    <?php
                    // Display success or error messages
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message'], $_SESSION['message_type']);
                    }
                    ?>
                    <h4>Change Password</h4>
                    <form action="profile_process.php" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>