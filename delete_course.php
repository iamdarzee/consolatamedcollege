<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $account_id = $_SESSION['account_id'];

    try {
        // Fetch the current courses_applied
        $stmt = $conn->prepare("SELECT courses_applied FROM applications WHERE student_id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $application = $result->fetch_assoc();

        if ($application) {
            $courses_applied = json_decode($application['courses_applied'], true);
            
            // Remove the course if it exists
            if (isset($courses_applied[$course_id])) {
                unset($courses_applied[$course_id]);

                // Update the applications table
                $courses_json = json_encode($courses_applied);
                $stmt_update = $conn->prepare("UPDATE applications SET courses_applied = ? WHERE student_id = ?");
                $stmt_update->bind_param("si", $courses_json, $account_id);
                $stmt_update->execute();

                echo json_encode(["success" => true, "message" => "Course deleted successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Course not found in application"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Application not found"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>