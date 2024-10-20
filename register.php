<?php
require_once './config.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$name = $dob = $gender = $id_number = $email = $password = $phone_number = $alt_phone = $address = $emergency_name = $emergency_phone = $emergency_relationship = "";
$name_err = $dob_err = $gender_err = $id_number_err = $email_err = $password_err = $phone_number_err = $address_err = $emergency_name_err = $emergency_phone_err = $emergency_relationship_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name validation
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Date of Birth validation
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter your date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }

    // Gender validation
    if (empty(trim($_POST["gender"]))) {
        $gender_err = "Please select your gender.";
    } else {
        $gender = trim($_POST["gender"]);
    }

    // ID/Passport number validation
    if (empty(trim($_POST["id_number"]))) {
        $id_number_err = "Please enter your ID/Passport number.";
    } else {
        $id_number = trim($_POST["id_number"]);
    }

    // Email validation
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Password validation
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Phone number validation
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter your phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Alternative phone number (optional)
    $alt_phone = trim($_POST["alt_phone"]);

    // Address validation
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter your address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Emergency contact validation
    if (empty(trim($_POST["emergency_name"]))) {
        $emergency_name_err = "Please enter an emergency contact name.";
    } else {
        $emergency_name = trim($_POST["emergency_name"]);
    }

    if (empty(trim($_POST["emergency_phone"]))) {
        $emergency_phone_err = "Please enter an emergency contact phone.";
    } else {
        $emergency_phone = trim($_POST["emergency_phone"]);
    }

    if (empty(trim($_POST["emergency_relationship"]))) {
        $emergency_relationship_err = "Please enter the emergency contact relationship.";
    } else {
        $emergency_relationship = trim($_POST["emergency_relationship"]);
    }

    // Check for errors before inserting into the database
    if (empty($name_err) && empty($dob_err) && empty($gender_err) && empty($id_number_err) && empty($email_err) && empty($password_err) && empty($phone_number_err) && empty($address_err) && empty($emergency_name_err) && empty($emergency_phone_err) && empty($emergency_relationship_err)) {

        mysqli_begin_transaction($conn);

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO student_accounts (name, dob, gender, id_number, email, password, phone_number, alt_phone, address, emergency_name, emergency_phone, emergency_relationship, register_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $param_name, $param_dob, $param_gender, $param_id_number, $param_email, $param_password, $param_phone_number, $param_alt_phone, $param_address, $param_emergency_name, $param_emergency_phone, $param_emergency_relationship);

            // Set parameters
            $param_name = $name;
            $param_dob = $dob;
            $param_gender = $gender;
            $param_id_number = $id_number;
            $param_email = $email;
            $param_password = $hashed_password;
            $param_phone_number = $phone_number;
            $param_alt_phone = $alt_phone;
            $param_address = $address;
            $param_emergency_name = $emergency_name;
            $param_emergency_phone = $emergency_phone;
            $param_emergency_relationship = $emergency_relationship;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_commit($conn);
                header("location: register_process.php");
                exit;
            } else {
                mysqli_rollback($conn);
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
    <title>Student Registration - Sister Leonella Consolata Medical College</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./media/logo.jpg" type="image/svg+xml">
</head>
<body class="bg-gray-600 text-white min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
        <!-- Main Content Container -->
        <div class="text-center mb-8 space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold text-white">Sister Leonella Consolata Medical College</h1>
            <p class="text-xl text-gray-300">Student Registration Form</p>
        </div>
        
        <!-- Registration Form Card -->
        <div class="bg-black shadow-2xl rounded-lg px-8 pt-8 pb-8 w-full max-w-2xl">
            <form action="register.php" method="post" class="space-y-6">
                <!-- Personal Information Section -->
                <div class="border-b border-gray-200 pb-4 mb-6 text-white text-center">
                    <h2 class="text-xl font-semibold text-white underline mb-4 ">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="name">Full Name</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="gender">Gender</label>
                            <select name="gender" id="gender" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <!-- ID/Passport Number -->
                        <div>
                            <label class="block text-white0 font-semibold mb-2" for="id_number">ID/Passport Number</label>
                            <input type="text" name="id_number" id="id_number" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="border-b border-gray-200 pb-4 mb-6 text-center">
                    <h2 class="text-xl font-semibold text-white mb-4 underline">Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="254" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required pattern="\d*" maxlength="12">
                        </div>

                        <!-- Alternative Phone Number -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="alt_phone">Alternative Phone Number</label>
                            <input type="text" name="alt_phone" id="alt_phone" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700">
                        </div>

                        <!-- Physical Address -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="address">Address</label>
                            <input type="text" name="address" id="address" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="border-b border-gray-200 pb-4 mb-6 text-center">
                    <h2 class="text-xl font-semibold text-white mb-4 underline">Emergency Contact</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="emergency_name">Contact Name</label>
                            <input type="text" name="emergency_name" id="emergency_name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="emergency_phone">Contact Phone</label>
                            <input type="text" name="emergency_phone" id="emergency_phone" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>

                        <!-- Relationship -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="emergency_relationship">Relationship</label>
                            <input type="text" name="emergency_relationship" id="emergency_relationship" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="border-b border-gray-200 pb-4 mb-6 text-center">
                    <h2 class="text-xl font-semibold text-white mb-4 underline">Account Security</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="password">Password</label>
                            <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                            <p class="text-sm text-gray-500 mt-1">Minimum 6 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-white font-semibold mb-2" for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-700" required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="register" value="Register" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Create Account
                </button>
            </form>
            
            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="./index.php" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Phone number validation
        document.getElementById('phone_number').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '');
            if (!e.target.value.startsWith('254')) {
                e.target.value = '254';
            }
            if (e.target.value.length > 12) {
                e.target.value = e.target.value.slice(0, 12);
            }
        });
    </script>
</body>
</html>
