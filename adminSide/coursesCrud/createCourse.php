<?php
session_start(); // Ensure session is started
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<?php include '../inc/dashHeader.php'; ?>
<?php
// Include config file
require_once "../../config.php";

// Initialize error variables
$course_id_err = "";

// Processing form data when form is submitted
if (isset($_POST['submit'])) {
    $course_category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $course_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $course_requirements = filter_input(INPUT_POST, 'requirements', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $course_duration = filter_input(INPUT_POST, 'duration', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $course_fee = filter_input(INPUT_POST, 'fee', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $intake_start = filter_input(INPUT_POST, 'intake_start', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $intake_end = filter_input(INPUT_POST, 'intake_end', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate that all required fields are filled
    if (empty($course_category) || empty($course_name) || empty($course_requirements) || empty($course_duration) || empty($course_fee) || empty($intake_start) || empty($intake_end)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
    } else {
        // Insert the course into the database
        $sql = "INSERT INTO courses (category, name, requirements, duration, fee, intake_start, intake_end) 
                VALUES ('$course_category', '$course_name', '$course_requirements', '$course_duration', '$course_fee', '$intake_start', '$intake_end')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('Course Added Successfully');
                window.location.href = 'createCourse.php';
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Create New Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .container {
            overflow: hidden;
            margin: 40px auto;
            max-width: 80%;
            padding: 0 20px;
        }

        @media (max-width: 767px) {
            .container {
                max-width: 100%;
                margin: 30px auto;
            }
        }

        h1 {
            color: chartreuse;
        }

        p {
            color: aqua;
        }

        label {
            color: white;
            font-size: 20px;
        }

        input, select, textarea {
            color: darkgreen;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<div class="container mt-16 items-center justify-center text-center bg-gray-600 rounded">
    <h1 class="text-3xl underline">Create New Course</h1>
    <p class="text-2xl font-bold">Please fill in the course information</p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="">

        <div class="form-group">
            <label for="category">Course Category:</label>
            <input type="text" name="category" id="category" placeholder="e.g., Nursing Courses" required class="form-control">
        </div>

        <div class="form-group">
            <label for="name">Course Name:</label>
            <input type="text" name="name" id="name" placeholder="e.g., Diploma in Nursing" required class="form-control">
        </div>

        <div class="form-group">
            <label for="requirements">Course Requirements:</label>
            <textarea name="requirements" id="requirements" rows="4" placeholder="Enter course requirements" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="duration">Duration:</label>
            <input type="text" name="duration" id="duration" placeholder="e.g., 3 Years" required class="form-control">
        </div>

        <div class="form-group">
            <label for="fee">Course Fee (in KES):</label>
            <input type="number" name="fee" id="fee" placeholder="e.g., 50000" step="0.01" min="0" required class="form-control">
        </div>

        <div class="form-group">
            <label for="intake_start">Intake Start Date:</label>
            <input type="date" name="intake_start" id="intake_start" required class="form-control">
        </div>

        <div class="form-group">
            <label for="intake_end">Intake End Date:</label>
            <input type="date" name="intake_end" id="intake_end" required class="form-control">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-dark" name="submit" value="Create Course">
        </div>
    </form>
</div>
