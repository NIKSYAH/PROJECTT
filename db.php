<?php
$host = "localhost";      // Your database host
$user = "root";           // Your database username
$password = "";           // Your database password
$dbname = "web-project"; // Your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
