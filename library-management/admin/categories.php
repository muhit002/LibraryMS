<?php
// Assumes you have a header file that starts the session and checks for admin login.
require_once '../includes/header.php'; 
require_once '../config/db_connection.php';

// Fetch all categories to display in the table
$sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-12">
            <h3>Manage Categories</h3>
            <hr>
            <!-- Display session messages for success or errors -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <!-- Column for listing existing categories -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Existing Categories</h4>
                </div>
                <div class="card-body">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                        <td>
                                            <a href="edit_category.php?id=<?php echo $row['category_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete_category.php?id=<?php echo $row['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No categories found. Add one using the form.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Column for adding a new category -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Add New Category</h4>
                </div>
                <div class="card-body">
                    <form action="add_category_process.php" method="POST">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
// Assumes a standard footer file.
require_once '../includes/footer.php'; 
?>