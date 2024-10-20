<?php
require_once "../../config.php";

// Check if 'id' is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $table_id = intval($_GET['id']);
} else {
    header("Location: ../panel/students-account-panel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User-provided input
    $provided_account_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $provided_password = $_POST['password'];

    try {
        // Prepare and execute the SQL statement
        $stmt = mysqli_prepare($conn, "SELECT admin_id, password_hash FROM admins WHERE admin_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $provided_account_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        if ($admin && password_verify($provided_password, $admin['password_hash'])) {
            // Log the authentication attempt
            $log_stmt = mysqli_prepare($conn, "INSERT INTO admin_logs (admin_id, action, target_id, ip_address) VALUES (?, 'delete_attempt', ?, ?)");
            mysqli_stmt_bind_param($log_stmt, "sis", $provided_account_id, $table_id, $_SERVER['REMOTE_ADDR']);
            mysqli_stmt_execute($log_stmt);

            // Successful authentication
            header("Location: ../accountCrud/deleteAccount.php?id=$table_id");
            exit();
        } else {
            $error_message = "Invalid credentials";
        }
    } catch (Exception $e) {
        // Log the error securely
        error_log("Database error: " . $e->getMessage());
        $error_message = "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../css/verifyAdmin.css" rel="stylesheet" />
</head>
<body>
    <div class="login-container">
        <div class="login_wrapper">
            <div class="wrapper">
                <h2 style="text-align: center;">Admin Login</h2>
                <h5>Admin Credentials needed to Delete Account</h5>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label>Admin Id</label>
                        <input type="number" name="admin_id" class="form-control" placeholder="Enter Admin ID" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Admin Password" required>
                    </div>
                    <button class="btn btn-light" type="submit" name="submit">Delete Account</button>
                    <a class="btn btn-danger" href="../panel/account-panel.php">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>