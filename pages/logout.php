<?php
// Start the session
if (!isset($_SESSION)) {
    session_start();
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the homepage or login page after logout
header("Location: /online-store/index.php"); // Change the path based on your actual file structure
exit();
?>