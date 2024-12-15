<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_management_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Failed to connect to the database: " . $conn->connect_error);
}
?>
