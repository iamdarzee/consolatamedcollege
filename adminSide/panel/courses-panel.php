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

// Include header
include '../inc/dashHeader.php';

// Fetch the list of courses from the database
$query = "SELECT * FROM courses";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .table-container {
            overflow-x: auto;
            margin: 20px;
        }

        h1 {
            color: cornflowerblue;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mt-12 mx-auto p-6">
        <h1 class="text-3xl font-bold text-center underline">Courses List</h1>
        <div class="text-center my-4">
            <a href="../coursesCrud/createCourse.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Course
            </a>
        </div>
        
        <div class="table-container">
            <table class="min-w-full bg-gray-400">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Requirements</th>
                        <th>Duration</th>
                        <th>Fee (KES)</th>
                        <th>Intake Start</th>
                        <th>Intake End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-white bg-black">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['category']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['requirements']}</td>
                                <td>{$row['duration']}</td>
                                <td>" . number_format($row['fee'], 2) . "</td>
                                <td>{$row['intake_start']}</td>
                                <td>{$row['intake_end']}</td>
                                <td>
                                    <a href='../coursesCrud/updateCourse.php?id={$row['id']}' class='text-blue-500 hover:text-blue-700'>Edit</a> |
                                    <a href='../coursesCrud/deleteCourse.php?id={$row['id']}' class='text-red-500 hover:text-red-700' onclick=\"return confirm('Are you sure you want to delete this course?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
