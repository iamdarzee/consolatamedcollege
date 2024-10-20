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
    $stmt = $conn->prepare("SELECT * FROM student_accounts WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "No student details found.";
        exit();
    }

    // Fetch available courses
    $sql_courses = "SELECT * FROM courses";
    $result_courses = $conn->query($sql_courses);

    // Fetch applied courses for the student
    $sql_applied_courses = "SELECT courses_applied FROM applications WHERE student_id = ?";
    $stmt_applied = $conn->prepare($sql_applied_courses);
    $stmt_applied->bind_param("i", $account_id);
    $stmt_applied->execute();
    $result_applied_courses = $stmt_applied->get_result();
    $applied_courses = $result_applied_courses->fetch_assoc();

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
    <title>Student Course Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./media/logo.jpg" type="image/x-icon">
    <style>
        body {
            background-color: #1F2937; /* Dark background for better contrast */
        }
        .container {
            margin: 0 auto;
            padding: 20px;
        }
        .details-container, .courses-container {
            background-color: darkcyan;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: white;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #F3F4F6; /* Light gray for the header */
        }
        .add-to-cart {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: auto; /* Center the button */
        }
        .add-to-cart:hover {
            background-color: #218838;
        }
        /* For mobile responsiveness */
        @media (max-width: 640px) {
            .courses-container {
                overflow-x: auto; /* Allow horizontal scrolling */
            }
        }

        .applied-courses-container {
        margin: 10 auto;
        padding: 30px;
        background-color: #f1f4f8;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .applied-courses-title {
        font-size: 2rem;
        color: #333;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 25px;
        position: relative;
    }

    .applied-courses-title:after {
        content: '';
        width: 60px;
        height: 4px;
        background-color: #ff8c00;
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    #applied-courses-list {
        list-style: none;
        padding: 0;
    }

    .course-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .course-card div {
        font-size: 1.1rem;
        color: #333;
    }

    .course-card div:last-child {
        font-size: 1rem;
        color: #777;
    }
    
    .nav-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .confirm-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mx-auto mt-10">
    <div class="details-container mb-10">
        <h1 class="text-2xl mb-4 text-center">Welcome, <br> <?php echo htmlspecialchars($student['name']); ?></h1>
        <div class="text-center">
            <a href="profile.php" class="nav-button">My Profile</a>
            <a href="my_applications.php" class="nav-button">My Applications</a>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="courses-container">
        <h2 class="text-xl mb-4 text-center underline">Available Courses</h2>

        <?php if ($result_courses->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Requirements</th>
                            <th>Duration</th>
                            <th>Fee</th>
                            <th>Intake Start</th>
                            <th>Intake End</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($course = $result_courses->fetch_assoc()): ?>
                            <tr class="bg-gray-300 border-b hover:bg-gray-100">
                                <td><?php echo htmlspecialchars($course['id']); ?></td>
                                <td><?php echo htmlspecialchars($course['category']); ?></td>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><?php echo htmlspecialchars($course['requirements']); ?></td>
                                <td><?php echo htmlspecialchars($course['duration']); ?></td>
                                <td><?php echo htmlspecialchars($course['fee']); ?></td>
                                <td><?php echo htmlspecialchars($course['intake_start']); ?></td>
                                <td><?php echo htmlspecialchars($course['intake_end']); ?></td>
                                <td>
                                    <button class="add-to-cart" onclick="addToCart(<?php echo $course['id']; ?>)">Add to Cart</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No courses available at the moment.</p>
        <?php endif; ?>
    </div>

   <!-- Applied Courses Section -->
    <div class="applied-courses-container mt-10" style="text-align:center; padding: 20px; background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 class="applied-courses-title" style="font-size: 1.75rem; margin-bottom: 20px; color: #333; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; text-decoration: underline;">Courses Applied</h2>
        
        <ul id="applied-courses-list" style="list-style-type: none; padding: 0;">
            <?php
            if ($applied_courses) {
                $courses_applied = json_decode($applied_courses['courses_applied'], true);
                foreach ($courses_applied as $course_id => $course_details) {
                    echo "
                    <li style='margin-bottom: 15px;'>
                        <div class='course-card' style='display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease-in-out;'>
                            <div style='font-size: 1.2rem; font-weight: 500; color: #444;'>"
                                . htmlspecialchars($course_details['name']) . 
                            "</div>
                            <div style='font-size: 1rem; color: #888;'>" 
                                . htmlspecialchars($course_details['fee']) . 
                            "</div>
                            <div style='font-size: 1rem; color: #888;'>" 
                                . htmlspecialchars($course_details['duration']) . 
                            "</div>
                            <button class='delete-button' onclick='deleteCourse($course_id)'>Delete</button>
                        </div>
                    </li>";
                }
            } else {
                echo "<li id='no-courses-message' style='color: #888;'>No courses applied yet.</li>";
            }
            ?>
        </ul>
        <button id="confirm-application" class="confirm-button" onclick="confirmApplication()">Confirm Application</button>
    </div>

</div>

</div>

<script>
    function addToCart(courseId) {
    var appliedCoursesCount = document.querySelectorAll('#applied-courses-list li').length;
    
    if (appliedCoursesCount >= 3) {
        alert("You can only add up to 3 courses to your cart.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Course added to cart successfully!");

                var appliedCoursesContainer = document.getElementById("applied-courses-list");
                var noCoursesMessage = document.getElementById("no-courses-message");

                // Remove the "No courses applied yet" message if it exists
                if (noCoursesMessage) {
                    noCoursesMessage.remove();
                }

                // Get the newly added course
                var newCourse = response.courses_applied[courseId];

                // Create a new list item for the newly applied course
                var listItem = document.createElement("li");
                listItem.style.marginBottom = "15px";

                var courseCard = document.createElement("div");
                courseCard.className = "course-card";
                courseCard.style.display = "flex";
                courseCard.style.justifyContent = "space-between";
                courseCard.style.alignItems = "center";
                courseCard.style.padding = "15px 20px";
                courseCard.style.backgroundColor = "#fff";
                courseCard.style.borderRadius = "8px";
                courseCard.style.boxShadow = "0 2px 6px rgba(0, 0, 0, 0.05)";
                courseCard.style.transition = "transform 0.2s ease-in-out";

                // Course name, fee, and duration divs
                var courseNameDiv = document.createElement("div");
                courseNameDiv.style.fontSize = "1.2rem";
                courseNameDiv.style.fontWeight = "500";
                courseNameDiv.style.color = "#444";
                courseNameDiv.textContent = newCourse.name;

                var courseFeeDiv = document.createElement("div");
                courseFeeDiv.style.fontSize = "1rem";
                courseFeeDiv.style.color = "#888";
                courseFeeDiv.textContent = newCourse.fee;

                var courseDurationDiv = document.createElement("div");
                courseDurationDiv.style.fontSize = "1rem";
                courseDurationDiv.style.color = "#888";
                courseDurationDiv.textContent = newCourse.duration;

                // Create the delete button
                var deleteButton = document.createElement("button");
                deleteButton.className = "delete-button";
                deleteButton.textContent = "Delete";
                deleteButton.onclick = function() {
                    deleteCourse(courseId);
                };

                // Append the divs and button to the course card
                courseCard.appendChild(courseNameDiv);
                courseCard.appendChild(courseFeeDiv);
                courseCard.appendChild(courseDurationDiv);
                courseCard.appendChild(deleteButton);

                // Append the course card to the list item
                listItem.appendChild(courseCard);

                // Append the new list item to the applied courses list
                appliedCoursesContainer.appendChild(listItem);

                // Disable the "Add to Cart" button if 3 courses are added
                if (appliedCoursesContainer.children.length >= 3) {
                    var addToCartButtons = document.querySelectorAll('.add-to-cart');
                    addToCartButtons.forEach(function(button) {
                        button.disabled = true;
                        button.style.backgroundColor = '#ccc';
                        button.style.cursor = 'not-allowed';
                    });
                }

            } else {
                alert(response.message || "Failed to add course.");
            }
        }
    };
    xhr.send("course_id=" + courseId);
}


    function deleteCourse(courseId) {
        if (confirm("Are you sure you want to delete this course from your application?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_course.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Course deleted successfully!");
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(response.message || "Failed to delete course.");
                    }
                }
            };
            xhr.send("course_id=" + courseId);
        }
    }

    function confirmApplication() {
    var appliedCoursesList = document.getElementById("applied-courses-list");
    var appliedCoursesCount = appliedCoursesList.getElementsByTagName("li").length;

    // Check if any courses have been applied
    if (appliedCoursesCount === 0 || document.getElementById("no-courses-message")) {
        alert("Please add courses to your application before confirming.");
        return; // Stop the function if no courses have been applied
    }

    if (confirm("Are you sure you want to confirm your application? This action cannot be undone.")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "confirm_application.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Application confirmed successfully!");
                    window.location.href = "my_applications.php"; // Redirect to My Applications page
                } else {
                    alert(response.message || "Failed to confirm application.");
                }
            }
        };
        xhr.send();
    }
}

</script>

</body>
</html>