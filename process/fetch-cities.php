<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['region'])) {
        $regionName = $conn->real_escape_string(trim($_POST['region']));
    
        $query = "SELECT id, city FROM location WHERE region = '$regionName'";
        $result = $conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            $cities = [];
            while ($row = $result->fetch_assoc()) {
                $cities[] = $row;
            }
            echo json_encode(['status' => 200, 'cities' => $cities]);
        } else {
            echo json_encode(['status' => 404, 'message' => 'No cities found']);
        }
    } else {
        $query = "SELECT id AS region_id, region FROM location GROUP BY region";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $regions = [];
            while ($row = $result->fetch_assoc()) {
                $regions[] = $row;
            }
            echo json_encode(['status' => 200, 'regions' => $regions]);
        } else {
            echo json_encode(['status' => 404, 'message' => 'No regions found']);
        }
    }
} else {
    echo json_encode(['status' => 405, 'message' => 'Invalid request method']);
}
?>