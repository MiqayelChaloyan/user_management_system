<?php
include '../includes/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
        $region = $_POST['region'];
        $city = $_POST['city'];

        $address = trim($_POST['address'] ?? '');

        if ($city !== 'Vanadzor') {
            $address = 'N / A';
        }

        $address = !empty($address) ? $address : 'N / A';


        $uploadDir = "../uploads/";

        // TODO: Query to get the old file name for the user
        $query = "SELECT file FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $old_file_name = $row['file']; // TODO: Get the old file name
        } else {
            $old_file_name = NULL; // TODO: If no file, set to NULL
        }

        $fileName = $old_file_name; // TODO: Default to NULL if no file is uploaded

        // TODO: Check if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $fileExt = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedTypes)) {
                // TODO: Generate a unique filename for the new image
                $fileName = uniqid("user_", true) . "." . $fileExt;
                $targetFilePath = $uploadDir . $fileName;

                // TODO: If there's an old file, delete it
                if (!empty($old_file_name)) {
                    $current_file_path = $uploadDir . $old_file_name;
                    // TODO: Check if the old file exists and delete it
                    if (file_exists($current_file_path)) {
                        unlink($current_file_path); // Delete old file
                    }
                }

                // TODO: Move the uploaded file to the server
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

        // TODO: Validate required fields
        if (empty($full_name) || empty($email) || empty($phone_number) || empty($region) || empty($city)) {
            $response = [
                'status' => 400,
                'message' => 'All fields are required.'
            ];
            http_response_code(400);
        } else {
            // TODO: Update user information in the database
            $query = "UPDATE users SET full_name = '$full_name', email = '$email', phone_number = '$phone_number', region = '$region', city = '$city', file = '$fileName', address='$address' WHERE id = $user_id";

            if (mysqli_query($conn, $query)) {
                $response = [
                    'status' => 200,
                    'message' => 'User updated successfully'
                ];
                http_response_code(200);
            } else {
                $error = mysqli_error($conn);
                $response = [
                    'status' => 500,
                    'message' => 'Failed to update user',
                    'error' => $error
                ];
                http_response_code(500);
            }
        }
    } else {
        $response = [
            'status' => 400,
            'message' => 'Invalid user ID'
        ];
        http_response_code(400);
    }
} else {
    $response = [
        'status' => 405,
        'message' => 'Invalid request method'
    ];
    http_response_code(405);
}

echo json_encode($response);

mysqli_close($conn);
exit;
