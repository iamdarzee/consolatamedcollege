<?php
// Start the session
session_start();

// Check if a staff member is logged in
if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true) {
    // Unset only the staff-related session variables
    unset($_SESSION['staff_logged_in']);
    unset($_SESSION['logged_staff_id']);
    unset($_SESSION['logged_staff_name']);

    // Optionally, you can regenerate the session ID for security
    session_regenerate_id(true);

    // Redirect the staff to the login page or homepage
    header("Location: login.php"); // Change this to your staff login page
    exit();
} else {
    // If no staff is logged in, redirect to the homepage
    header("Location: ../../index.php");
    exit();
}
?>