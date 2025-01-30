<?php
include '../includes/db.php';

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = (int) $_POST['id'];

    // TODO: Delete local file
    $uploadDir = "../uploads/";
    $query = "SELECT file FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $fileName = $row['file'];
    $current_file_path = $uploadDir . $fileName;

    if (file_exists($current_file_path)) {
        unlink($current_file_path);
    }

    // TODO: Delete queries
    $deleteDetailsQuery = "DELETE FROM user_details WHERE user_id = $userId";
    $deleteQuery = "DELETE FROM users WHERE id = $userId";


    if (mysqli_query($conn, $deleteDetailsQuery) && mysqli_query($conn, $deleteQuery)) {
        $response = [
            'status' => 200,
            'message' => 'User and related details deleted successfully.'
        ];
    } else {
        $response = [
            'status' => 500,
            'message' => 'Failed to delete user: ' . mysqli_error($conn)
        ];
    }
} else {
    $response = [
        'status' => 400,
        'message' => 'Invalid request or missing user ID.'
    ];
}

echo json_encode($response);
mysqli_close($conn);
?>
