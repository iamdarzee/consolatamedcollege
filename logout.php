<?php
// Start session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: index.php");
exit;
?>
