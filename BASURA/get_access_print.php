<?php
// ajax/get_all_logs_for_print.php
include '../conn.php'; 

header('Content-Type: application/json');

$status = $_GET['status'] ?? 'all';
$room = $_GET['room'] ?? 'all';
$access_type = $_GET['access_type'] ?? 'all';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// 1. Define the base query
$sql = "SELECT 
            al.Log_id,
            u.F_name,
            u.L_name,
            al.Rfid_tag,
            u.Role,
            r.Room_code AS Room_code,
            CASE 
                WHEN al.Access_type IN ('Entry', 'Exit') AND u.Role = 'Student' THEN 'DOOR'
                WHEN al.Status = 'granted' AND u.Role IN ('Faculty', 'Admin') THEN 'DOOR & POWER'
                ELSE 'DOOR'
            END AS device_type,
            al.Access_time,
            al.Access_type,
            al.Status
        FROM access_log al
        LEFT JOIN users u ON al.User_id = u.User_id
        LEFT JOIN classrooms r ON al.Room_id = r.Room_id
        WHERE 1=1";

$params = [];
$types = "";

// 2. Append filters to $sql (NOT $query)
if ($status !== 'all') {
    $sql .= " AND al.Status = ?";
    $params[] = $status;
    $types .= "s";
}
if ($room !== 'all') {
    // Changed 'c.Room_code' to 'r.Room_code' to match your JOIN alias
    $sql .= " AND r.Room_code = ?"; 
    $params[] = $room;
    $types .= "s";
}
if ($access_type !== 'all') {
    $sql .= " AND al.Access_type = ?";
    $params[] = $access_type;
    $types .= "s";
}
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND DATE(al.Access_time) BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= "ss";
}

$sql .= " ORDER BY al.Access_time DESC";

// 3. Prepare and Execute
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    echo json_encode(['success' => true, 'logs' => $logs]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>