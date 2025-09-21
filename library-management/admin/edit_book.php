<?php
require_once '../includes/header.php';
require_once '../config/db_connection.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Check for book ID in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: books.php');
    exit;
}

$book_id = $_GET['id'];

// Fetch the book's details
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // No book found with that ID
    header('Location: books.php');
    exit;
}
$book = $result->fetch_assoc();
$stmt->close();

// Fetch all categories
$category_result = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Edit Book</h1>
                    <p class="card-subtitle text-muted">Update the details for "<?php echo htmlspecialchars($book['title']); ?>"</p>
                </div>
                <div class="card-body">
                    <form action="edit_book_process.php" method="POST">
                        <!-- Hidden input to store the book ID -->
                        <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <?php
                                while($cat = $category_result->fetch_assoc()) {
                                    $selected = ($cat['category_id'] == $book['category_id']) ? 'selected' : '';
                                    echo "<option value='{$cat['category_id']}' {$selected}>" . htmlspecialchars($cat['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="copies" class="form-label">Total Copies</label>
                            <input type="number" class="form-control" id="copies" name="total_copies" min="1" value="<?php echo $book['total_copies']; ?>" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="books.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Book</button>
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