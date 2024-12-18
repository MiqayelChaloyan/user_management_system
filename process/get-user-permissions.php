<?php
include '../includes/db.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Fetch the single global permissions row
        $query = "SELECT can_update, can_remove FROM user_permissions LIMIT 1";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $permissions = $result->fetch_assoc();
            echo json_encode(['status' => 200, 'permissions' => $permissions]);
        } else {
            echo json_encode(['status' => 404, 'message' => 'Global permissions not found']);
        }
    } catch (Exception $e) {
        // Log error and return response
        error_log("Error fetching permissions: " . $e->getMessage());
        echo json_encode(['status' => 500, 'message' => 'Internal server error']);
    }
} else {
    // Handle invalid request method
    echo json_encode(['status' => 405, 'message' => 'Invalid request method']);
}
?>
