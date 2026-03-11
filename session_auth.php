<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);

// If NOT logged in and NOT on the login page -> Go to Login
if (!isset($_SESSION['admin_id']) && $currentPage !== 'login.php') {
    header("Location: login.php");
    exit();
}

// If ALREADY logged in and trying to go to Login -> Go to Index
if (isset($_SESSION['admin_id']) && $currentPage === 'login.php') {
    header("Location: index.php");
    exit();
}
?>