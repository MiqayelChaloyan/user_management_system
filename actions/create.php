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
    
    $uploadDir = "../uploads/";
    $fileName = NULL; // Default to NULL if no file is uploaded

    // Check if an image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $fileExt = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedTypes)) {
            // Generate a unique filename
            $fileName = uniqid("user_", true) . "." . $fileExt;
            $targetFilePath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $response = [
                    'status' => 500,
                    'message' => 'Error uploading image.'
                ];
                echo json_encode($response);
                exit;
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'Invalid file format. Allowed: JPG, JPEG, PNG, GIF.'
            ];
            echo json_encode($response);
            exit;
        }
    }

    // Insert user into the database (with or without file)
    $query = "INSERT INTO `users` (`full_name`, `email`, `phone_number`, `region`, `city`, `file`) 
    VALUES ('$full_name', '$email', '$phone_number', '$region', '$city', '$fileName')";

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
