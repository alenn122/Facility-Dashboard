<?php
// ajax/get_rooms.php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'facility-dashboard');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// UPDATED SQL: Added grace_period, allow_extension, and double_tap_exit
$sql = "SELECT 
            Room_id, 
            Room_code, 
            Capacity, 
            Classroom_type, 
            Status, 
            FLOOR, 
            grace_period, 
            allow_extension, 
            double_tap_exit 
        FROM classrooms 
        ORDER BY Room_code";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure values are numbers for JavaScript to handle correctly
        $row['Room_id'] = (int)$row['Room_id'];
        $row['grace_period'] = (int)$row['grace_period'];
        $row['allow_extension'] = (int)$row['allow_extension'];
        $row['double_tap_exit'] = (int)$row['double_tap_exit'];
        $rooms[] = $row;
    }
}

$conn->close();

// Return JSON response
echo json_encode([
    'success' => true,
    'rooms' => $rooms,
    'total' => count($rooms)
]);
?>