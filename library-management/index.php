<?php
session_start();
// This page acts as a router.
// If a member is logged in, send them to their dashboard.
// Otherwise, send them to the login page.
if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true) {
    header('Location: member_dashboard.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>