<?php
include '../includes/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $region = trim($_POST['region']);
    $city = trim($_POST['city']);

    $query = "INSERT INTO `users` (`full_name`, `email`, `phone_number`, `region`, `city`) 
              VALUES ('$full_name', '$email', '$phone_number', '$region', '$city')";
    
    if ($conn->query($query)) {
        $response = [
            'status' => 200,
            'message' => 'User created successfully.'
        ];
        http_response_code(200);
    } else {
        $response = [
            'status' => 500,
            'message' => 'Error creating user: ' . $conn->error
        ];
        http_response_code(500);
    }

    echo json_encode($response);
}
?>
