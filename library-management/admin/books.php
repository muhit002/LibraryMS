<?php
// Include header and database connection
require_once '../includes/header.php';
require_once '../config/db_connection.php';

// Security Check: Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all books from the database along with their category names
// We use a JOIN to get the category_name from the categories table
$sql = "SELECT b.*, c.category_name 
        FROM books b 
        JOIN categories c ON b.category_id = c.category_id 
        ORDER BY b.title ASC";

$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1>Manage Books</h1>
            <a href="add_book.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Book</a>
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
                            <th style="width: 80px;">Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Total</th>
                            <th>Avail.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($book = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo !empty($book['cover_image']) ? '../uploads/book_covers/' . htmlspecialchars($book['cover_image']) : 'https://placehold.co/60x80?text=No+Image'; ?>"
                                            alt="Cover for <?php echo htmlspecialchars($book['title']); ?>"
                                            width="60" class="img-thumbnail">
                                    </td>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                                    <td><?php echo htmlspecialchars($book['category_name']); ?></td>
                                    <td><?php echo $book['total_copies']; ?></td>
                                    <td><?php echo $book['available_copies']; ?></td>
                                    <td>
                                        <a href="edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="delete_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this book?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No books found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Close the database connection and include the footer
$conn->close();
require_once '../includes/footer.php';
?>