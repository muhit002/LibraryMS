<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style> body { background-color: #f8f9fa; } .card { box-shadow: 0 4px 6px rgba(0,0,0,.1); border: 0;} </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-book-half"></i> LibraryMS Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="publicNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="browse_books.php">Browse Books</a>
            </li>
            <?php if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true): ?>
                <!-- Show these links if member IS logged in -->
                <li class="nav-item">
                    <a class="nav-link" href="member_dashboard.php">My Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="member_logout.php">Logout</a>
                </li>
            <?php else: ?>
                <!-- Show these links if member IS NOT logged in -->
                 <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
  </div>
</nav>
<div class="container">