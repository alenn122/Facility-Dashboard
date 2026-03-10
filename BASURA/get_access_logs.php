<?php
// ajax/get_access_logs.php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'facility-dashboard');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get filter parameters - Debug output
error_log("GET parameters: " . print_r($_GET, true));

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$room = isset($_GET['room']) ? $_GET['room'] : 'all';
$access_type = isset($_GET['access_type']) ? $_GET['access_type'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$offset = ($page - 1) * $limit;

// Debug
error_log("Status: $status, Room: $room, Access Type: $access_type, Search: $search");

// Build base query
$sql = "SELECT 
            al.Log_id,
            u.F_name,          -- Changed from al.F_name to u.F_name
            u.L_name,          -- Changed from al.L_name to u.L_name
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


$sql_count = "SELECT COUNT(*) as total FROM access_log al
              LEFT JOIN users u ON al.User_id = u.User_id
              LEFT JOIN classrooms r ON al.Room_id = r.Room_id

              WHERE 1=1";

$conditions = [];
$params = [];
$types = '';

// Apply filters - FIXED CONDITIONS
if ($status != 'all') {
    $conditions[] = "al.Status = ?";
    $params[] = $status;
    $types .= 's';
    error_log("Added status filter: $status");
}

if ($room != 'all') {
    $conditions[] = "r.Room_code = ?";
    $params[] = $room;
    $types .= 's';
    error_log("Added room filter: $room");
}

if ($access_type != 'all') {
    $conditions[] = "al.Access_type = ?";
    $params[] = $access_type;
    $types .= 's';
    error_log("Added access type filter: $access_type");
}

if (!empty($search)) {
    // Check if search is numeric for Log_id or User_id
    if (is_numeric($search)) {
        $conditions[] = "(al.Log_id = ? OR 
                         al.User_id = ? OR 
                         al.Rfid_tag LIKE ? OR 
                         r.Room_code LIKE ? OR
                         u.F_name LIKE ? OR
                         u.L_name LIKE ?)";
        $search_term = "%$search%";
        array_push($params, intval($search), intval($search), $search_term, $search_term, $search_term, $search_term);
        $types .= 'iissss';
    } else {
        $conditions[] = "(al.Rfid_tag LIKE ? OR 
                         r.Room_code LIKE ? OR
                         u.F_name LIKE ? OR
                         u.L_name LIKE ?)";
        $search_term = "%$search%";
        array_push($params, $search_term, $search_term, $search_term, $search_term);
        $types .= 'ssss';
    }
    error_log("Added search filter: $search (numeric: " . (is_numeric($search) ? 'true' : 'false') . ")");
}

// Add conditions to queries
if (!empty($conditions)) {
    $condition_string = " AND " . implode(" AND ", $conditions);
    $sql .= $condition_string;
    $sql_count .= $condition_string;
}

// Debug SQL
error_log("SQL: $sql");
error_log("Count SQL: $sql_count");
error_log("Params: " . print_r($params, true));
error_log("Types: $types");

// Count total records
$count_params = $params;
$count_types = $types;

if (!empty($conditions)) {
    $stmt_count = $conn->prepare($sql_count);
    if ($count_params) {
        $stmt_count->bind_param($count_types, ...$count_params);
    }
    $stmt_count->execute();
    $count_result = $stmt_count->get_result();
    $total_row = $count_result->fetch_assoc();
    $total = $total_row['total'];
    $stmt_count->close();
    error_log("Total count: $total");
} else {
    $result_count = $conn->query($sql_count);
    $total_row = $result_count->fetch_assoc();
    $total = $total_row['total'];
    error_log("Total count (no params): $total");
}

// Add ordering and pagination to main query only
$sql_paginated = $sql . " ORDER BY al.Access_time DESC LIMIT ? OFFSET ?";

// Prepare params for paginated query
$paginated_params = $params;
$paginated_params[] = $limit;
$paginated_params[] = $offset;
$paginated_types = $types . 'ii';

// Debug final SQL
error_log("Final SQL with pagination: $sql_paginated");
error_log("Final params: " . print_r($paginated_params, true));
error_log("Final types: $paginated_types");

// Prepare and execute main query
$stmt = $conn->prepare($sql_paginated);
if ($paginated_params) {
    $stmt->bind_param($paginated_types, ...$paginated_params);
}
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

$stmt->close();
$conn->close();

// Debug response
error_log("Returning " . count($logs) . " logs");

// Return JSON response
echo json_encode([
    'success' => true,
    'logs' => $logs,
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
    'filters' => [
        'status' => $status,
        'room' => $room,
        'access_type' => $access_type,
        'search' => $search
    ]
]);
?>


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

<?php
// ajax/get_analytics.php
include '../conn.php'; // Adjust path to your DB connection file

$response = [
    'today_count' => 0,
    'energy_events' => 0,
    'denied_count' => 0,
    'peak_room' => 'None',
    'chart_labels' => [],
    'chart_data' => []
];

// 1. Total Taps Today
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_log WHERE DATE(Access_time) = CURDATE()");
$row = mysqli_fetch_assoc($res);
$response['today_count'] = $row['total'];

// 2. Denied Attempts
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_log WHERE Status = 'denied' AND DATE(Access_time) = CURDATE()");
$row = mysqli_fetch_assoc($res);
$response['denied_count'] = $row['total'];

// 3. Energy Saving Events (Assuming 'Exit' or 'System Off' logic turns off utilities)
$res = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_log WHERE Access_type = 'Exit' AND DATE(Access_time) = CURDATE()");
$row = mysqli_fetch_assoc($res);
$response['energy_events'] = $row['total'];

// 4. Peak Room (Updated to use Room_id)
$res = mysqli_query($conn, "SELECT Room_id, COUNT(*) as count FROM access_log WHERE DATE(Access_time) = CURDATE() GROUP BY Room_id ORDER BY count DESC LIMIT 1");
if($row = mysqli_fetch_assoc($res)) {
    $response['peak_room'] = "Room " . $row['Room_id'];
}

// 5. Chart Data (Last 24 Hours)
for ($i = 23; $i >= 0; $i--) {
    $hour = date('H', strtotime("-$i hours"));
    $displayHour = date('ga', strtotime("-$i hours")); // e.g., 9am, 10am
    
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM access_log WHERE HOUR(Access_time) = '$hour' AND DATE(Access_time) = CURDATE()");
    $row = mysqli_fetch_assoc($res);
    
    $response['chart_labels'][] = $displayHour;
    $response['chart_data'][] = (int)$row['total'];
}

echo json_encode($response);
?>