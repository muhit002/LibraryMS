<?php
require_once 'includes/public_header.php';

// If member is already logged in, redirect away
if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true) {
    header('Location: member_dashboard.php');
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card mt-4">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Create an Account</h3>
                
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-' . ($_SESSION['message_type'] ?? 'danger') . '">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message'], $_SESSION['message_type']);
                }
                ?>

                <form action="register_process.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                     <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/public_footer.php'; ?>