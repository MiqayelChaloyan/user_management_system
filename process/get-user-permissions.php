<?php
include '../includes/db.php';
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $query = "SELECT can_update, can_remove FROM user_permissions LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $permissions = mysqli_fetch_assoc($result);
            http_response_code(200);
            echo json_encode(['status' => 200, 'permissions' => $permissions]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 404, 'message' => 'Global permissions not found']);
        }
    } catch (Exception $e) {
        error_log("Error fetching permissions: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['status' => 500, 'message' => 'Internal server error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 405, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
