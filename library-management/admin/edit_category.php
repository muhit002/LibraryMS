<?php
// CORRECTED: Use the existing header.php file.
require_once '../includes/header.php'; 
require_once '../config/db_connection.php';

// Ensure an ID was passed via the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$category_id = (int)$_GET['id'];

// Fetch the category details to pre-fill the form
$sql = "SELECT category_name FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $category = $result->fetch_assoc();
} else {
    // If no category is found, redirect with an error message
    $_SESSION['message'] = "Category not found.";
    $_SESSION['message_type'] = "danger";
    header('Location: categories.php');
    exit;
}
$stmt->close();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Category</h4>
                </div>
                <div class="card-body">
                    <form action="edit_category_process.php" method="POST">
                        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" 
                                   value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                        </div>
                        <a href="categories.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
// CORRECTED: Use the existing footer.php file.
require_once '../includes/footer.php'; 
?>