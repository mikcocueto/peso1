<?php
// DATABASE CONNECTION
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = "";     // Default XAMPP password is empty
$dbname = "pesodb";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
