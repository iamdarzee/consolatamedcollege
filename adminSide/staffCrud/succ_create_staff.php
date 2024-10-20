<?php
require_once "../../config.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_VALIDATE_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password']; // Password will be hashed later

    if (!$staff_id || !$email || !$staff_name || !$phone_number || !$role || !$password) {
        // If validation fails, redirect back with an error
        header("Location: create-staff.php?error=validation");
        exit();
    }

    $conn = $conn;

    // Start a transaction to ensure consistency across multiple table inserts
    $conn->begin_transaction();

    try {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert Data into Staff Table
        $insert_staff_query = "INSERT INTO staff (staff_id, email, staff_name, role, phone_number, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_staff = $conn->prepare($insert_staff_query);
        $stmt_staff->bind_param("isssss", $staff_id, $email, $staff_name, $role, $phone_number, $hashed_password);

        // Execute the query to insert data into the staff table
        if (!$stmt_staff->execute()) {
            throw new Exception("Error creating staff: " . $stmt_staff->error);
        }

        $stmt_staff->close();

        // Commit the transaction if everything is successful
        $conn->commit();
        $message = "Staff created successfully.";
        $iconClass = "fa-check-circle";
        $cardClass = "alert-success";
        $bgColor = "#D4F4DD"; // Custom background color for success
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor = "#FFA7A7"; // Custom background color for error
    } finally {
        // Close the connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
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
        .alert-success i {
            color: #5DBE6F;
        }
        .alert-danger {
            background-color: #FFA7A7;
        }
        .alert-danger i {
            color: #F25454;
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
        .alert-icon {
            padding-bottom: 20px;
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
        // Function to show the message card as a pop-up and start the countdown
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
                    window.location.href = "../panel/staff-panel.php";
                }
            }, 1000);
        }

        // Show the message card and start the countdown when the page is loaded
        window.onload = showPopup;
    </script>
</body>
</html>
