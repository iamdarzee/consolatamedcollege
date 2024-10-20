<?php
// Include config file
require_once "../../config.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Check if the staff_id parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the staff_id from the URL and sanitize it
    $staff_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$staff_id) {
        // Invalid staff_id, display an error message
        echo "Invalid staff ID.";
        exit();
    }

    // Disable foreign key checks (only if absolutely necessary)
    $conn->begin_transaction();

    try {
        // Disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0;");

        // Construct the DELETE query
        $deleteSQL = "DELETE FROM staff WHERE staff_id = ?";

        // Prepare the DELETE query
        $stmt = $conn->prepare($deleteSQL);

        // Bind the parameter
        $stmt->bind_param("i", $staff_id);

        // Execute the DELETE query
        if ($stmt->execute()) {
            // Commit the transaction if deletion is successful
            $conn->commit();
            // Staff member successfully deleted, redirect back to the main page
            header("Location: ../panel/staff-panel.php");
            exit();
        } else {
            // Rollback the transaction if there is an error
            $conn->rollback();
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $conn->rollback();
        echo $e->getMessage();
    } finally {
        // Enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1;");
        // Close the statements and connection
        $stmt->close();
        $conn->close();
    }
} else {
    // staff_id parameter not set, display an error message
    echo "No staff ID provided.";
    exit();
}
?>
