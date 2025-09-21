<?php
// (The top PHP part is the same)
require_once '../includes/header.php';
require_once '../config/db_connection.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
$category_sql = "SELECT * FROM categories ORDER BY category_name ASC";
$category_result = $conn->query($category_sql);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><h1 class="card-title">Add New Book</h1></div>
                <div class="card-body">
                    <!-- IMPORTANT: Added enctype for file uploads -->
                    <form action="add_book_process.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php
                                if ($category_result->num_rows > 0) {
                                    while($row = $category_result->fetch_assoc()) {
                                        echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="copies" class="form-label">Total Copies</label>
                            <input type="number" class="form-control" id="copies" name="total_copies" min="1" value="1" required>
                        </div>
                        <!-- NEW: File input for cover image -->
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image (Optional)</label>
                            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/gif">
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="books.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Book</button>
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