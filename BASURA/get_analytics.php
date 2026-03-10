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