<?php
session_start(); // Ensure session is started
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<?php include '../inc/dashHeader.php'; ?>
<?php
// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$staff_id = $staff_id_err = "";
$staff_name = $staff_name_err = "";
$email = $email_err = "";  
$register_date = $register_date_err = "";
$phone_number = $phone_number_err = ""; 
$role = $role_err = ""; 
$password = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection: check if the CSRF token is valid
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Validate staff ID
    if (empty(trim($_POST["staff_id"]))) {
        $staff_id_err = "Staff ID is required.";
    } else {
        $staff_id = trim($_POST["staff_id"]);
    }

    // Validate staff name
    if (empty(trim($_POST["staff_name"]))) {
        $staff_name_err = "Staff name is required.";
    } else {
        $staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Email is required.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Phone number is required.";
    } elseif (!preg_match('/^\+?[0-9]*$/', trim($_POST["phone_number"]))) {
        $phone_number_err = "Invalid phone number format.";
    } else {
        $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Role is required.";
    } else {
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Password is required.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($staff_id_err) && empty($staff_name_err) && empty($email_err) && empty($phone_number_err) && empty($role_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO staff (staff_id, staff_name, email, phone_number, role, password) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssss", $param_staff_id, $param_staff_name, $param_email, $param_phone_number, $param_role, $param_password);
            
            // Set parameters
            $param_staff_id = $staff_id;
            $param_staff_name = $staff_name;
            $param_email = $email;
            $param_phone_number = $phone_number;
            $param_role = $role;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to success page
                header("location: succ_create_staff.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}

// Function to get the next available staff ID
function getNextAvailableStaffID($conn) {
    $sql = "SELECT MAX(staff_id) as max_staff_id FROM staff";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_staff_id = $row['max_staff_id'] + 1;
    return $next_staff_id;
}

// Get the next available Staff ID
$next_staff_id = getNextAvailableStaffID($conn);

// Generate a new CSRF token
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .container {
            overflow: hidden;
            margin: 0 auto; /* Center the container horizontally */
            max-width: 600px; /* Set maximum width to prevent overflow */
            padding: 0 20px; /* Add some padding to the sides */
        }
        h1 {
            color: chartreuse;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: aqua;
        }
        label {
            color: white;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 20px;
        }
        input {
            color: darkgreen;
        }
    </style>
</head>
<body>
<div class="container mt-16 items-center justify-center text-center bg-gray-600 rounded">
    <h1 class="text-3xl underline">Create New Staff</h1>
    <p class="text-2xl font-bold">Please fill in the Staff Information</p>

    <form method="POST" action="succ_create_staff.php">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="form-group">
            <label for="staff_id" class="form-label">Staff ID:</label>
            <input min="1" type="number" name="staff_id" class="form-control <?php echo (!empty($staff_id_err)) ? 'is-invalid' : ''; ?>" id="staff_id" required value="<?php echo $next_staff_id; ?>" readonly><br>
            <div class="invalid-feedback">
                <?php echo $staff_id_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="staff_name">Staff Name:</label>
            <input type="text" name="staff_name" id="staff_name" class="form-control <?php echo (!empty($staff_name_err)) ? 'is-invalid' : ''; ?>" required><br>
            <div class="invalid-feedback">
                <?php echo $staff_name_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" name="role" id="role" class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>" required><br>
            <div class="invalid-feedback">
                <?php echo $role_err; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" required><br>
            <div class="invalid-feedback">
                <?php echo $email_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="phone_number" class="form-label">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" required><br>
            <div class="invalid-feedback">
                <?php echo $phone_number_err; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required><br>
            <div class="invalid-feedback">
                <?php echo $password_err; ?>
            </div>
        </div>
        
        <div class="form-group mb-5">
            <input type="submit" class="btn btn-dark" value="Create Staff">
        </div>
    </form>
</div>
</body>
</html>

<?php include '../inc/dashFooter.php'; ?>