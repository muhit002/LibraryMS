<?php
// We'll create a separate header for the public-facing pages
require_once 'includes/public_header.php';

// If member is already logged in, redirect to their dashboard
if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true) {
    header('Location: member_dashboard.php');
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card mt-5">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Member Login</h3>
                
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']);
                }
                ?>

                <form action="member_login_process.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/public_footer.php'; ?>