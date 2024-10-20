<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $account_id = $_SESSION['account_id'];

    // Fetch student details
    $stmt = $conn->prepare("SELECT * FROM student_accounts WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        $name = $student['name'];
        $email = $student['email'];
        $phone_number = $student['phone_number'];

        // Fetch course details
        $stmt_course = $conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt_course->bind_param("i", $course_id);
        $stmt_course->execute();
        $course = $stmt_course->get_result()->fetch_assoc();

        if ($course) {
            $course_details = [
                'name' => $course['name'],
                'duration' => $course['duration'],
                'fee' => $course['fee']
            ];

            // Check if the student already has an application
            $stmt_check = $conn->prepare("SELECT * FROM applications WHERE student_id = ?");
            $stmt_check->bind_param("i", $account_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // If application exists, update the courses_applied field
                $application = $result_check->fetch_assoc();
                $courses_applied = json_decode($application['courses_applied'], true);
                
                // Check if the course limit has been reached
                if (count($courses_applied) >= 3) {
                    echo json_encode(["success" => false, "message" => "You can only add up to 3 courses to your cart."]);
                    exit;
                }

                // Check if the course is already applied
                if (!isset($courses_applied[$course_id])) {
                    $courses_applied[$course_id] = $course_details;

                    $stmt_update = $conn->prepare("UPDATE applications SET courses_applied = ? WHERE student_id = ?");
                    $courses_json = json_encode($courses_applied);
                    $stmt_update->bind_param("si", $courses_json, $account_id);
                    $stmt_update->execute();
                }
            } else {
                // If no application exists, insert a new record
                $courses_applied = [$course_id => $course_details];
                $courses_json = json_encode($courses_applied);

                $stmt_insert = $conn->prepare("INSERT INTO applications (student_id, name, email, phone_number, courses_applied) VALUES (?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("issss", $account_id, $name, $email, $phone_number, $courses_json);
                $stmt_insert->execute();
            }

            // Fetch the updated list of applied courses
            $stmt_fetch = $conn->prepare("SELECT courses_applied FROM applications WHERE student_id = ?");
            $stmt_fetch->bind_param("i", $account_id);
            $stmt_fetch->execute();
            $result_fetch = $stmt_fetch->get_result();
            $updated_application = $result_fetch->fetch_assoc();

            // Return the updated courses_applied as JSON
            echo json_encode([
                "success" => true,
                "courses_applied" => json_decode($updated_application['courses_applied'], true),
                "message" => "Course added successfully"
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Course not found."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Student not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>