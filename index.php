<?php
require_once './config.php';
session_start();


$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT * FROM student_accounts WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row["password"])) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["email"] = $email;
                        $_SESSION["account_id"] = $row["account_id"];
                        header("location: ./application.php");
                        exit;
                    } else {
                        $password_err = "Invalid password. Please try again.";
                    }
                } else {
                    $email_err = "No account found with this email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sister Leonella Consolata Medical College</title>
    <meta name="title" content="Sister Leonella Consolata Medical College">
    <meta name="author" content="Darzee">
    <meta name="description" content="The Official site of Sister Leonella Consolata Medical College">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./media/lock.jpg" type="image/svg+xml">
</head>
<body class="bg-gray-700 text-white min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <!-- Main Content Container -->
        <div class="text-center mb-8 space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold text-white">Welcome to Sister Leonella Consolata Medical College</h1>
        </div>
        
        <!-- Login Form Card -->
        <div class="bg-black backdrop-blur-sm shadow-2xl rounded-lg px-8 pt-8 pb-8 w-full max-w-md">
            <form action="index.php" method="post" class="space-y-6">
                <div>
                    <label for="email" class="block text-white font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-700" placeholder="Enter your email" required>
                    <span class="text-white text-xs italic"><?php echo $email_err; ?></span>
                </div>
                
                <div>
                    <label for="password" class="block text-white font-semibold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-700" placeholder="Enter your password" required>
                    <span class="text-red-500 text-xs italic"><?php echo $password_err; ?></span>
                </div>
                
                <button type="submit" name="submit" value="Login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">Sign In</button>
            </form>
            
            <!-- Links -->
            <div class="mt-6 space-y-4 text-center">
                <p class="text-gray-300">
                    Don't have an account? 
                    <a href="register.php" class="text-blue-600 hover:text-blue-700 font-semibold">Register here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
