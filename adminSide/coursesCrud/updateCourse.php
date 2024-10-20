<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Include config file
require_once "../../config.php";

// Check if the user is logged in
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Check if the course ID is provided in the URL
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Fetch the existing course details
    $query = "SELECT * FROM courses WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $course_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $course = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    // Check if the course exists
    if (!$course) {
        die("Course not found.");
    }

    // Update course details if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get updated course details
        $category = $_POST['category'];
        $name = $_POST['name'];
        $requirements = $_POST['requirements'];
        $duration = $_POST['duration'];
        $fee = $_POST['fee'];
        $intake_start = $_POST['intake_start'];
        $intake_end = $_POST['intake_end'];

        // Update the course in the database
        $update_query = "UPDATE courses SET category = ?, name = ?, requirements = ?, duration = ?, fee = ?, intake_start = ?, intake_end = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $update_query)) {
            // Bind the parameters correctly using 's' for strings, including date fields
            mysqli_stmt_bind_param($stmt, "sssssssi", $category, $name, $requirements, $duration, $fee, $intake_start, $intake_end, $course_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the courses list page after a successful update
                header("Location: ../panel/courses-panel.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }

    }
} else {
    die("Invalid course ID.");
}

// Include header
include '../inc/dashHeader.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Edit Course</h1>
        
        <form action="updateCourse.php?id=<?php echo $course_id; ?>" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($course['category']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Course Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($course['name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="requirements">Requirements</label>
                <textarea name="requirements" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($course['requirements']); ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="duration">Duration</label>
                <input type="text" name="duration" value="<?php echo htmlspecialchars($course['duration']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="fee">Fee (KES)</label>
                <input type="number" name="fee" step="0.01" value="<?php echo htmlspecialchars($course['fee']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="intake_start">Intake Start</label>
                <input type="date" name="intake_start" value="<?php echo htmlspecialchars($course['intake_start']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="intake_end">Intake End</label>
                <input type="date" name="intake_end" value="<?php echo htmlspecialchars($course['intake_end']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Course
                </button>
                <a href="coursesPanel.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
