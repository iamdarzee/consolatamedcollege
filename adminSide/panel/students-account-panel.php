<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Include config file
require_once "../../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User-provided input
    $provided_staff_id = $_POST['staff_id'];
    $provided_password = $_POST['password'] ?? '';

    // Query to fetch staff record based on provided staff_id
    $query = "SELECT * FROM staff WHERE staff_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("i", $provided_staff_id);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            if (password_verify($provided_password, $stored_password)) {
                // Password matches, login successful
                $_SESSION['staff_logged_in'] = true;
                $_SESSION['logged_staff_id'] = $provided_staff_id;
                $_SESSION['logged_staff_name'] = $row['staff_name'];
            } else {
                $login_err = "Incorrect password. Please try again.";
            }
        } else {
            $login_err = "Staff ID not found. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Check if the user is logged in
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    // User is not logged in, show the login form
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <meta name="title" content="Sister Leonella Consolata Medical College">
    <meta name="author" content="Darzee">
    <link rel="shortcut icon" href="../../images/lock.jpg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="flex justify-center items-center h-screen w-full">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Staff Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-4">
                <label for="staff_id" class="block text-gray-700 font-bold mb-2">Staff ID</label>
                <input type="number" id="staff_id" name="staff_id" placeholder="Enter Staff ID" required
                    class="px-4 py-2 w-full border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required
                    class="px-4 py-2 w-full border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Login
            </button>
        </form>
    </div>
</div>

</body>
</html>
<?php
    exit();
}

// User is logged in, continue with the rest of the page

include '../inc/dashHeader.php';
?>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table-container {
            overflow-x: auto;
        }

        h1 {
            color: cornflowerblue;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        table {
            min-width: 600px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<div class="container bg-gray-600">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                <h1 class="my-2 text-center font-black underline text-3xl">Account Details</h1>
        </div>
                
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <input required type="text" id="search" name="search" class="form-control" placeholder="Enter Account ID, Email">
                            </div>
                            <div class="col-span-1 flex justify-between">
                                <button type="submit" class="px-4 py-2 text-white bg-gray-800 rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700">Search</button>
                                <a href="account-panel.php" class="btn btn-light">Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-container">
                     <?php
                // Include config file
                require_once "../../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT *
                                FROM student_accounts
                                WHERE email LIKE '%$search%' OR account_id LIKE '%$search%'
                                ORDER BY account_id;";
                    } else {
                        // Default query to fetch all student_accounts
                        $sql = "SELECT *
                                FROM student_accounts
                                ORDER BY account_id;";
                    }
                } else {
                    // Default query to fetch all student_accounts
                    $sql = "SELECT *
                            FROM student_accounts
                            ORDER BY account_id;";
                }

                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-bordered text-white">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Name</th>";
                        echo "<th>Email</th>";
                        echo "<th>Register Date</th>";
                        echo "<th>Phone Number</th>";
                        echo "<th>Address</th>";
                        echo "<th>Delete</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['register_date'] . "</td>";
                            echo "<td>" . $row['phone_number'] . "</td>";
                            echo "<td>" . $row['address'] . "</td>";
                            echo "<td>";
                            $deleteSQL = "DELETE FROM student_accounts WHERE account_id = '" . $row['account_id'] . "';";
                           echo '<a href="../accountCrud/deleteAccountVerify.php?id=' . $row['account_id'] . '" title="Delete Record" data-toggle="tooltip" '
                                    . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Account?\n\nThis will alter other modules related to this Account!\n\')"><span class="fa fa-trash text-red-500"></span></a>';
                           echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($conn);
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>