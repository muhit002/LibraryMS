<?php
// The password you want to hash
$passwordToHash = 'password123';

// Hash the password using PHP's recommended algorithm
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

// Display the hashed password
echo 'The hash for "' . $passwordToHash . '" is:<br>';
echo '<strong>' . $hashedPassword . '</strong>';
?>