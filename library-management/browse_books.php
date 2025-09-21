<?php
// This is the complete code for browse_books.php

require_once 'includes/public_header.php';
require_once 'config/db_connection.php';

// --- FETCH CATEGORIES FOR THE DROPDOWN ---
$category_sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
$category_result = $conn->query($category_sql);
$categories = [];
if ($category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}


// --- DYNAMIC SEARCH AND FILTER LOGIC ---
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Base SQL query
$sql = "SELECT b.book_id, b.title, b.author, b.cover_image, c.category_name, b.total_copies, b.available_copies
        FROM books b
        JOIN categories c ON b.category_id = c.category_id";

// Arrays to hold dynamic parts of the query
$where_conditions = [];
$params = [];
$param_types = '';

// 1. Add condition for TEXT search
if (!empty($search_term)) {
    $where_conditions[] = "(b.title LIKE ? OR b.author LIKE ?)";
    $params[] = "%" . $search_term . "%";
    $params[] = "%" . $search_term . "%";
    $param_types .= 'ss';
}

// 2. Add condition for CATEGORY filter
if (!empty($selected_category)) {
    $where_conditions[] = "b.category_id = ?";
    $params[] = $selected_category;
    $param_types .= 'i';
}

// 3. Combine conditions into the final query
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(' AND ', $where_conditions);
}

$sql .= " ORDER BY b.title ASC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);

// Bind parameters if any exist
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Browse Our Book Collection</h1>
            <p class="lead">Here are all the books available in our library.</p>
            <hr>
        </div>
    </div>

    <!-- --- UPDATED SEARCH & FILTER FORM --- -->
    <div class="row mb-4">
        <div class="col-md-10 offset-md-1">
            <form action="browse_books.php" method="GET" class="mb-3">
                <div class="input-group">
                    <!-- Category Dropdown -->
                    <select name="category" class="form-select" style="max-width: 200px;">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($selected_category == $category['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Text Search Input -->
                    <input type="text" name="search" class="form-control" placeholder="Search by book title or author..." value="<?php echo htmlspecialchars($search_term); ?>">
                    
                    <button class="btn btn-primary" type="submit">Search</button>
                    <?php if (!empty($search_term) || !empty($selected_category)): ?>
                        <a href="browse_books.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>


    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($book = $result->fetch_assoc()): ?>
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 text-center">
                        <img src="<?php echo !empty($book['cover_image']) ? 'uploads/book_covers/' . htmlspecialchars($book['cover_image']) : 'https://placehold.co/200x280?text=No+Image'; ?>"
                            class="card-img-top"
                            alt="Cover for <?php echo htmlspecialchars($book['title']); ?>"
                            style="height: 280px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text text-muted">by <?php echo htmlspecialchars($book['author']); ?></p>
                            <div class="mt-auto">
                                <?php
                                if ($book['available_copies'] > 0) {
                                    echo '<span class="badge bg-success">Available</span>';
                                } else {
                                    echo '<span class="badge bg-danger">Checked Out</span>';
                                }
                                echo " <small class='text-muted'>({$book['available_copies']} of {$book['total_copies']})</small>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                 <!-- More informative context-aware message -->
                <?php if (!empty($search_term) || !empty($selected_category)): ?>
                    <p class="text-center lead">No books found matching your search criteria.</p>
                <?php else: ?>
                    <p class="text-center lead">No books found in the library collection.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'includes/public_footer.php';
?>