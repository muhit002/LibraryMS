<?php
// Check if a session has already been started
if (session_status() == PHP_SESSION_NONE) {
    // If not, start a new session
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Optional: Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* A little custom styling for a minimal look */
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            border: 0;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
  <div class="container">
    <!-- UPDATED: Brand link now points to the dashboard -->
    <a class="navbar-brand" href="dashboard.php">
        <i class="bi bi-book-half"></i> LibraryMS
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="books.php">Manage Books</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="members.php">Manage Members</a>
            </li>

            <!-- === NEW: MANAGE CATEGORIES LINK ADDED HERE === -->
            <li class="nav-item">
              <a class="nav-link" href="categories.php">Manage Categories</a>
            </li>
            <!-- === END NEW === -->
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="transactionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Transactions
              </a>
              <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
                <li><a class="dropdown-item" href="issue_book.php"><i class="bi bi-journal-arrow-up me-2"></i>Issue a Book</a></li>
                <li><a class="dropdown-item" href="issued_books.php"><i class="bi bi-journals me-2"></i>View Issued Books</a></li>
              </ul>
            </li>

            <li class="nav-item dropdown ms-lg-3">
              <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-gear-fill me-2"></i>Profile Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">