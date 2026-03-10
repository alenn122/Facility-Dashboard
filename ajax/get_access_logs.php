<?php
// ajax/get_access_logs.php
include '../conn.php'; 
header('Content-Type: application/json');

$action      = $_GET['action'] ?? 'table';
$status      = $_GET['status'] ?? 'all';
$room        = $_GET['room'] ?? 'all';
$access_type = $_GET['access_type'] ?? 'all';
$search      = isset($_GET['search']) ? trim($_GET['search']) : '';
$from_date   = $_GET['from_date'] ?? '';
$to_date     = $_GET['to_date'] ?? '';

// --- ACTION: ANALYTICS ---
if ($action === 'analytics') {
    $response = ['today_count' => 0, 'energy_events' => 0, 'denied_count' => 0, 'peak_room' => 'None', 'chart_labels' => [], 'chart_data' => []];
    
    $response['today_count'] = $conn->query("SELECT COUNT(*) as total FROM access_log WHERE DATE(Access_time) = CURDATE()")->fetch_assoc()['total'];
    $response['denied_count'] = $conn->query("SELECT COUNT(*) as total FROM access_log WHERE Status = 'denied' AND DATE(Access_time) = CURDATE()")->fetch_assoc()['total'];
    $response['energy_events'] = $conn->query("SELECT COUNT(*) as total FROM access_log WHERE Access_type = 'Exit' AND DATE(Access_time) = CURDATE()")->fetch_assoc()['total'];
    $res = $conn->query("SELECT r.Room_code, COUNT(*) as count 
                     FROM access_log al 
                     JOIN classrooms r ON al.Room_id = r.Room_id 
                     WHERE DATE(al.Access_time) = CURDATE() 
                     GROUP BY al.Room_id 
                     ORDER BY count DESC LIMIT 1");

    if($row = $res->fetch_assoc()) { 
        $response['peak_room'] = $row['Room_code']; 
    }
    // 5. Chart Data (Current Day: 12am to 11pm)
    $response['chart_labels'] = [];
    $response['chart_data'] = [];

    for ($hour = 0; $hour <= 23; $hour++) {
        // Format hour for SQL (00, 01, 02...)
        $hourFormatted = str_pad($hour, 2, "0", STR_PAD_LEFT);
        
        // Format label for the graph (12am, 1am, 2am...)
        $displayLabel = date("ga", strtotime("$hour:00"));
        
        // Count taps for this specific hour of the CURRENT day
        $res = $conn->query("SELECT COUNT(*) as total 
                            FROM access_log 
                            WHERE HOUR(Access_time) = '$hourFormatted' 
                            AND DATE(Access_time) = CURDATE()");
        $row = $res->fetch_assoc();
        
        $response['chart_labels'][] = $displayLabel;
        $response['chart_data'][] = (int)$row['total'];
    }

    echo json_encode($response);
    exit;
}

// --- ACTION: TABLE & PRINT ---
// Base Query matches your JavaScript requirements
$base_sql = " FROM access_log al
              LEFT JOIN users u ON al.User_id = u.User_id
              LEFT JOIN classrooms r ON al.Room_id = r.Room_id
              WHERE 1=1";

$conditions = "";
$params = [];
$types = "";

if ($status != 'all') { $conditions .= " AND al.Status = ?"; $params[] = $status; $types .= 's'; }
if ($room != 'all') { $conditions .= " AND r.Room_code = ?"; $params[] = $room; $types .= 's'; }
if ($access_type != 'all') { $conditions .= " AND al.Access_type = ?"; $params[] = $access_type; $types .= 's'; }
if (!empty($from_date) && !empty($to_date)) { $conditions .= " AND DATE(al.Access_time) BETWEEN ? AND ?"; $params[] = $from_date; $params[] = $to_date; $types .= "ss"; }

if (!empty($search)) {
    $conditions .= " AND (al.Rfid_tag LIKE ? OR r.Room_code LIKE ? OR u.F_name LIKE ? OR u.L_name LIKE ?)";
    $st = "%$search%";
    array_push($params, $st, $st, $st, $st);
    $types .= 'ssss';
}

$total = 0;
if ($action === 'table') {
    // Count total for pagination
    $stmt_c = $conn->prepare("SELECT COUNT(*) as total" . $base_sql . $conditions);
    if (!empty($params)) $stmt_c->bind_param($types, ...$params);
    $stmt_c->execute();
    $total = $stmt_c->get_result()->fetch_assoc()['total'];

    $limit = intval($_GET['limit'] ?? 50);
    $offset = (intval($_GET['page'] ?? 1) - 1) * $limit;
    $order_limit = " ORDER BY al.Access_time DESC LIMIT ? OFFSET ?";
    $final_params = array_merge($params, [$limit, $offset]);
    $final_types = $types . "ii";
} else {
    $order_limit = " ORDER BY al.Access_time DESC";
    $final_params = $params;
    $final_types = $types;
}

// Select query including your device_type logic
$select = "SELECT al.*, u.F_name, u.L_name, u.Role, r.Room_code,
           CASE 
               WHEN al.Access_type IN ('Entry', 'Exit') AND u.Role = 'Student' THEN 'DOOR'
               WHEN al.Status = 'granted' AND u.Role IN ('Faculty', 'Admin') THEN 'DOOR & POWER'
               ELSE 'DOOR'
           END AS device_type ";

$stmt = $conn->prepare($select . $base_sql . $conditions . $order_limit);
if (!empty($final_params)) $stmt->bind_param($final_types, ...$final_params);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'logs' => $logs, 'total' => $total]);