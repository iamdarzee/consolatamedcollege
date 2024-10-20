<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_id = $_SESSION['account_id'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Fetch the application
        $stmt = $conn->prepare("SELECT * FROM applications WHERE student_id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $application = $result->fetch_assoc();

        if ($application) {
            // Insert into my_applications table
            $stmt_insert = $conn->prepare("INSERT INTO my_applications (student_id, name, email, phone_number, courses_applied) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("issss", $application['student_id'], $application['name'], $application['email'], $application['phone_number'], $application['courses_applied']);
            $stmt_insert->execute();

            // Delete from applications table
            $stmt_delete = $conn->prepare("DELETE FROM applications WHERE student_id = ?");
            $stmt_delete->bind_param("i", $account_id);
            $stmt_delete->execute();

            // Commit transaction
            $conn->commit();

            echo json_encode(["success" => true, "message" => "Application confirmed successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Application not found"]);
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>