<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "resume_builder";

$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>