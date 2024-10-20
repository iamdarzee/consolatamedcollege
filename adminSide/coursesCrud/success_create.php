<?php
require_once "../../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $course_category = $_POST["category"];
    $course_name = $_POST["name"];
    $course_requirements = $_POST["requirements"];
    $course_duration = $_POST["duration"];
    $course_fee = $_POST["fee"];
    $intake_start = $_POST["intake_start"];
    $intake_end = $_POST["intake_end"];

    // Prepare the SQL query to check if the course name already exists
    $check_query = "SELECT name FROM courses WHERE name = ?";
    $check_stmt = $link->prepare($check_query);
    $check_stmt->bind_param("s", $course_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // Check if the course name already exists
    if ($check_result->num_rows > 0) {
        $message = "The course name is already in use.<br>Please try again with a different course name.";
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor = "#FFA7A7"; // Custom background color for error
    } else {
        // Prepare the SQL query for inserting a new course
        $insert_query = "INSERT INTO courses (category, name, requirements, duration, fee, intake_start, intake_end) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $link->prepare($insert_query);

        // Bind the parameters
        $stmt->bind_param("sssssss", $course_category, $course_name, $course_requirements, $course_duration, $course_fee, $intake_start, $intake_end);

        // Execute the query
        if ($stmt->execute()) {
            $message = "Course created successfully.";
            $iconClass = "fa-check-circle";
            $cardClass = "alert-success";
            $bgColor = "#D4F4DD"; // Custom background color for success
        } else {
            $message = "Error: $insert_query<br>{$link->error}";
            $iconClass = "fa-times-circle";
            $cardClass = "alert-danger";
            $bgColor = "#FFA7A7"; // Custom background color for error
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the check statement and the connection
    $check_stmt->close();
    $link->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }
        h1 {
            color: #88B04B;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        p {
            color: #404F5E;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }
        i.checkmark {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left: -15px;
        }
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
        .alert-success {
            background-color: <?php echo $bgColor; ?>;
        }
        .alert-danger {
            background-color: #FFA7A7;
        }
        .alert-icon {
            padding-bottom: 20px;
        }
        .custom-x {
            color: #F25454;
            font-size: 100px;
            line-height: 200px;
        }
        .alert-box {
            max-width: 300px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="card <?php echo $cardClass; ?>" style="display: none;">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
            <?php if ($iconClass === 'fa-check-circle'): ?>
                <i class="checkmark">✓</i>
            <?php else: ?>
                <i class="custom-x" style="font-size: 100px; line-height: 200px;">✘</i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($cardClass === 'alert-success') ? 'Success' : 'Error'; ?></h1>
        <p><?php echo $message; ?></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">Redirecting back in <span id="countdown">3</span></div>

    <script>
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "block";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "createCourse.php";
                }
            }, 1000);
        }

        window.onload = showPopup;

        function hidePopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "none";
            setTimeout(function () {
                window.location.href = "createCourse.php";
            }, 3000);
        }

        setTimeout(hidePopup, 3000);
    </script>
</body>
</html>
