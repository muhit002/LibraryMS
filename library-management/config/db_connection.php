<?php
// --- Database Configuration ---
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Your MySQL username (default is 'root')
define('DB_PASSWORD', '');     // Your MySQL password (default is empty)
define('DB_NAME', 'library_management_system'); // The database name you created
define('PENALTY_PER_DAY', 50); 
// --- Attempt to connect to MySQL database ---
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// --- Check Connection ---
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Optional: Set character set to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

?>