<?php 
$servername = "localhost";
$database = "sister_leonella_college_db";
$username = "root";
$password = "6dW7p2_5)sCTHfOu";
// Create connection using mysqli_connect function
$conn = mysqli_connect($servername, $username, $password, $database);
// Connection Check
if (!$conn) {
    die("Connection failed: {$conn->connect_error}");
}

?>