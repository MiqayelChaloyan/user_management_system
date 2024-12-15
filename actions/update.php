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
        $country = isset($_POST['country']) ? trim($_POST['country']) : '';

        if (empty($full_name) || empty($email) || empty($phone_number) || empty($country)) {
            $response = [
                'status' => 400,
                'message' => 'All fields are required.'
            ];
            http_response_code(400);
        } else {
            $query = "UPDATE users SET full_name = '$full_name', email = '$email', phone_number = '$phone_number', country = '$country' WHERE id = $user_id";

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
?>
