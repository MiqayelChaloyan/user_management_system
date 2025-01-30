<?php
include '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // TODO: Ensure the user_id parameter is present
    if (isset($_GET['user_id'])) {
        $userId = $_GET['user_id'];

        // Fetch user details with a direct query
        $query = "SELECT age, gender, dob, info, job FROM user_details WHERE user_id = $userId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                echo json_encode($row); // TODO: Return data as JSON
            } else {
                echo json_encode([]); // TODO: Return empty array if no data
            }
        } else {
            echo json_encode(['error' => 'Query failed']); // TODO: Error if query fails
        }
    } else {
        echo json_encode(['error' => 'user_id parameter missing']); // TODO: Error if no user_id is provided
    }
} else {
    echo json_encode(['error' => 'Invalid request method']); // TODO: Error for non-GET request
}
?>
