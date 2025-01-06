<?php
include '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure the user_id parameter is present
    if (isset($_GET['user_id'])) {
        $userId = $_GET['user_id'];

        // Fetch user details with a query
        $query = "SELECT age, gender, dob, info, job FROM user_details WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode($row); // Return data as JSON
        } else {
            echo json_encode([]); // Return empty array if no data
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo json_encode(['error' => 'user_id parameter missing']); // Error if no user_id is provided
    }
} else {
    echo json_encode(['error' => 'Invalid request method']); // Error for non-GET request
}
?>
