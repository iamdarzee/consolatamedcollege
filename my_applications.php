<?php
// Start session
session_start();

// Check if the student is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Database connection
require_once './config.php';

try {
    // Fetch student details using account_id from session
    $account_id = $_SESSION["account_id"];

    // Fetch all applied courses for the student, ordered by created_at descending
    $sql_applied_courses = "SELECT courses_applied, created_at FROM my_applications WHERE student_id = ? ORDER BY created_at DESC";
    $stmt_applied = $conn->prepare($sql_applied_courses);
    $stmt_applied->bind_param("i", $account_id);
    $stmt_applied->execute();
    $result_applied_courses = $stmt_applied->get_result();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./media/lock.jpg" type="image/svg+xml">
</head>
<body class="bg-gray-700">
    <div class="container mx-auto mt-10 px-4">
        <h1 class="text-4xl text-center font-bold text-white underline mb-8">My Applications</h1>

        <?php if ($result_applied_courses->num_rows > 0): ?>
            <div class="space-y-8">
                <?php while ($row = $result_applied_courses->fetch_assoc()): ?>
                    <?php $courses_applied = json_decode($row['courses_applied'], true); ?>
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-xl font-bold text-blue-700 mb-4">Applied on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></h2>
                        <table class="table-auto w-full border-collapse">
                            <thead>
                                <tr class="bg-blue-100">
                                    <th class="px-4 py-2 text-left text-blue-900">Course Name</th>
                                    <th class="px-4 py-2 text-left text-blue-900">Fee</th>
                                    <th class="px-4 py-2 text-left text-blue-900">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses_applied as $course_details): ?>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-gray-700"><?php echo htmlspecialchars($course_details['name']); ?></td>
                                        <td class="px-4 py-2 text-gray-700"><?php echo htmlspecialchars($course_details['fee']); ?></td>
                                        <td class="px-4 py-2 text-gray-700"><?php echo htmlspecialchars($course_details['duration']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">You have not applied for any courses yet.</p>
        <?php endif; ?>

        <div class="mt-10 text-center">
            <a href="application.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg mr-4">Apply for Courses</a>
            <a href="profile.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg mr-4">My Profile</a>
            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">Logout</a>
        </div>
    </div>
</body>
</html>
