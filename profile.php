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

    // Update student details if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
        // Get updated values from the form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $alt_phone = $_POST['alt_phone'];
        $emergency_name = $_POST['emergency_name'];
        $emergency_phone = $_POST['emergency_phone'];
        $emergency_relationship = $_POST['emergency_relationship'];
        $address = $_POST['address'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $id_number = $_POST['id_number'];

        // Prepare update query
        $update_stmt = $conn->prepare("UPDATE student_accounts SET name=?, email=?, phone_number=?, alt_phone=?, emergency_name=?, emergency_phone=?, emergency_relationship=?, address=?, dob=?, gender=?, id_number=? WHERE account_id=?");
        $update_stmt->bind_param("sssssssssssi", $name, $email, $phone_number, $alt_phone, $emergency_name, $emergency_phone, $emergency_relationship, $address, $dob, $gender, $id_number, $account_id);
        
        if ($update_stmt->execute()) {
            // Redirect to profile page after successful update
            header("location: profile.php");
            exit;
        } else {
            echo "Error updating profile: " . $update_stmt->error;
        }
    }

    // Flag to determine if the edit form should be displayed
    $isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';

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
    <title><?php echo htmlspecialchars($student['name']); ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-black">
    <div class="container mx-auto mt-10 px-4 mb-10">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-black mb-6 text-center"><?php echo htmlspecialchars($student['name']); ?>'s Profile</h1>

            <!-- Profile Display -->
            <div class="bg-gray-300 p-6 rounded-lg mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $fields = [
                        'Name' => 'name',
                        'Email' => 'email',
                        'Phone Number' => 'phone_number',
                        'Alternate Phone' => 'alt_phone',
                        'Emergency Contact Name' => 'emergency_name',
                        'Emergency Contact Phone' => 'emergency_phone',
                        'Emergency Contact Relationship' => 'emergency_relationship',
                        'Address' => 'address',
                        'Date of Birth' => 'dob',
                        'Gender' => 'gender',
                        'ID Number' => 'id_number',
                        'Registration Date' => 'register_date'
                    ];

                    foreach ($fields as $label => $field) {
                        echo '<div class="mb-4">';
                        echo '<h3 class="text-sm font-semibold text-gray-600">' . $label . '</h3>';
                        echo '<p class="text-lg text-gray-800">' . htmlspecialchars($student[$field]) . '</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4 mb-8">
                <a href="application.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Course Application</a>
                <a href="my_applications.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">My Applications</a>
                <a href="?edit=true" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Edit Profile</a>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Logout</a>
            </div>

            <!-- Edit Profile Form -->
            <?php if ($isEditing): ?>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Profile</h2>
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php
                        $formFields = [
                            'name' => 'Name',
                            'email' => 'Email',
                            'phone_number' => 'Phone Number',
                            'alt_phone' => 'Alternate Phone',
                            'emergency_name' => 'Emergency Contact Name',
                            'emergency_phone' => 'Emergency Contact Phone',
                            'emergency_relationship' => 'Emergency Contact Relationship',
                            'address' => 'Address',
                            'dob' => 'Date of Birth',
                            'id_number' => 'ID Number'
                        ];

                        foreach ($formFields as $field => $label) {
                            $type = ($field === 'email') ? 'email' : ($field === 'dob' ? 'date' : 'text');
                            echo '<div>';
                            echo '<label for="' . $field . '" class="block text-sm font-medium text-gray-700 mb-1">' . $label . ':</label>';
                            echo '<input type="' . $type . '" id="' . $field . '" name="' . $field . '" value="' . htmlspecialchars($student[$field]) . '" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />';
                            echo '</div>';
                        }
                        ?>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender:</label>
                            <select id="gender" name="gender" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($student['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-center space-x-4">
                        <button type="submit" name="update_profile" class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Update Profile</button>
                        <a href="profile.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
