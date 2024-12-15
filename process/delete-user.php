<?php
include '../includes/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $userId = (int) $_POST['id'];

    $deleteQuery = "DELETE FROM users WHERE id = $userId";

    if (mysqli_query($conn, $deleteQuery)) {
        $response = [
            'status' => 200,
            'message' => 'User deleted successfully.'
        ];
        http_response_code(200);
    } else {
        $response = [
            'status' => 500,
            'message' => 'Error deleting user.'
        ];
        http_response_code(500);
    }
} else {
    $response = [
        'status' => 400,
        'message' => 'Invalid request.'
    ];
    http_response_code(400);
}

echo json_encode($response);
mysqli_close($conn);
exit;
?>
