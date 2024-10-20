<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$staff_id = $password = "";
$login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if staff ID and password are empty
    if (empty(trim($_POST["staff_id"])) || empty(trim($_POST["password"]))) {
        $login_err = "Staff ID or password is empty.";
    } else {
        // Prepare a select statement
        $sql = "SELECT staff_id, password FROM staff WHERE staff_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_staff_id);

            // Set parameters
            $param_staff_id = trim($_POST["staff_id"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if staff ID exists, if yes then verify password
                switch (mysqli_stmt_num_rows($stmt)) {
                    case 1:
                        mysqli_stmt_bind_result($stmt, $staff_id, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($_POST["password"], $hashed_password)) {
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["staff_id"] = $staff_id;

                                // Redirect user to account panel
                                header("Location: ../panel/students-account-panel.php");
                                exit;
                            } else {
                                // Password is incorrect
                                $login_err = "Incorrect password. Please try again.";
                            }
                        }
                        break;
                    default:
                        $login_err = "Staff ID not found. Please try again.";
                        break;
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
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
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
        .alert-danger {
            background-color: #F8D7DA;
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
    <?php if(!empty($login_err)): ?>
        <div class="card alert-danger">
            <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
                <i class="custom-x">✘</i>
            </div>
            <h1>Error</h1>
            <p><?php echo $login_err; ?></p>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="login.php">Back to Login</a>
        </div>
    <?php endif; ?>
</body>
</html>