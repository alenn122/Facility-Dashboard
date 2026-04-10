<?php
date_default_timezone_set('Asia/Manila');

// 1. CONFIGURATION
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "facility-dashboard";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// --- FETCH GLOBAL SETTINGS ---
$global_settings = [];
$get_globals = $conn->query("SELECT * FROM system_settings");
if($get_globals) {
    while($s_row = $get_globals->fetch_assoc()){
        $global_settings[$s_row['setting_key']] = $s_row['setting_value'];
    }
}

// --------------------------------------------------------------------
// 2. THE DYNAMIC HEARTBEAT (Ping from ESP32)
// --------------------------------------------------------------------
if (isset($_POST['ping'])) {
    $mac = $_POST['mac'];
    $conn->query("UPDATE devices SET last_seen = NOW(), status = 'Online' WHERE mac_address = '$mac'");
    
    $res = $conn->query("SELECT d.room_id, d.device_type, c.Status, c.grace_period FROM devices d JOIN classrooms c ON d.room_id = c.Room_id WHERE d.mac_address = '$mac' LIMIT 1");
    
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $rid = $row['room_id'];
        $dtype = strtoupper($row['device_type']);

        if ($row['Status'] == 'Occupied') {
            // Check if an Admin/Faculty is inside (Shutdown Override)
            $last_user_query = $conn->query("SELECT u.Role FROM access_log l JOIN users u ON l.User_id = u.User_id WHERE l.Room_id = '$rid' AND l.Status = 'granted' AND l.Access_type = 'Entry' ORDER BY l.Access_time DESC LIMIT 1");
            
            if ($last_user_query && $last_user_query->num_rows > 0) {
                $u_role = $last_user_query->fetch_assoc()['Role'];
                // Check policy table to see if this role keeps power on
                $policy = $conn->query("SELECT can_override_shutdown FROM access_policies WHERE role = '$u_role' AND (device_type = '$dtype' OR device_type = '*') LIMIT 1")->fetch_assoc();

                if ($policy && $policy['can_override_shutdown'] == 1) {
                    echo "PONG"; exit();
                }
            }

            // --- HIERARCHICAL GRACE PERIOD LOGIC ---
            $day = date('D'); $time = date('H:i:s');
            $sched = $conn->query("SELECT End_time FROM schedule WHERE Room_id = '$rid' AND Day = '$day' AND '$time' BETWEEN Start_time AND End_time LIMIT 1");

            if ($sched->num_rows == 0) {
                // Schedule ended, find the last session to calculate grace
                $last_sched = $conn->query("SELECT End_time FROM schedule WHERE Room_id = '$rid' AND Day = '$day' AND End_time <= '$time' ORDER BY End_time DESC LIMIT 1")->fetch_assoc();
                
                // Use Room Specific (if > 0) else fallback to Global
                $active_grace = ($row['grace_period'] > 0) ? $row['grace_period'] : ($global_settings['global_grace_period'] ?? 15);
                $seconds_since_end = time() - strtotime($last_sched['End_time']);

                if ($seconds_since_end < ($active_grace * 60)) {
                    // Check for 1-minute warning for extension
                    if ($seconds_since_end >= (($active_grace * 60) - 60)) { echo "WARNING_EXTEND"; exit(); }
                    echo "PONG"; exit();
                } else {
                    $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$rid'");
                    echo "FORCE_OFF"; exit();
                }
            }
        }
    }
    echo "PONG"; exit();
}

// --------------------------------------------------------------------
// 3. ACCESS REQUEST (RFID TAP)
// --------------------------------------------------------------------
$mac = $_POST['mac'] ?? ''; 
$rfid = $_POST['rfid'] ?? '';

// LOOKUP ROOM & DEVICE (Including custom logic toggles)
$dev_res = $conn->query("SELECT d.room_id, d.device_type, c.double_tap_exit, c.allow_extension FROM devices d JOIN classrooms c ON d.room_id = c.Room_id WHERE d.mac_address = '$mac' LIMIT 1");
if (!$dev_res || $dev_res->num_rows == 0) { echo "UNKNOWN_DEVICE"; exit(); }
$device = $dev_res->fetch_assoc();

$room_id = $device['room_id'];
$device_type = strtoupper($device['device_type']);

// USER LOOKUP
$user_query = $conn->query("SELECT * FROM users WHERE Rfid_tag='$rfid' AND Status='Active'");
if ($user_query->num_rows == 0) {
    echo "DENIED"; 
    log_access(null, $rfid, $room_id, null, 'Entry', 'denied', $device_type);
    exit();
}
$user = $user_query->fetch_assoc();
$user_id = $user['User_id'];
$u_role = $user['Role'];

// SMART ENTRY/EXIT TOGGLE
$last_log = $conn->query("SELECT Access_type, Access_time FROM access_log WHERE User_id = '$user_id' AND Room_id = '$room_id' AND Status = 'granted' ORDER BY Access_time DESC LIMIT 1")->fetch_assoc();
$access_type = ($last_log && $last_log['Access_type'] == 'Entry') ? 'Exit' : 'Entry';

// --- INTENT DISAMBIGUATION (Double-Tap vs Extension) ---
if ($access_type == 'Exit' && $u_role == 'Faculty') {
    $time_since_tap = time() - strtotime($last_log['Access_time']);
    
    // Use Room-specific setting if customized, otherwise use Global Default
    $dtap_enabled = ($device['double_tap_exit'] == 1) ? true : (($global_settings['global_double_tap'] ?? 1) == 1);
    
    // Double-tap (less than 5 seconds) forces an EXIT during a warning/grace period
    if ($dtap_enabled && $time_since_tap < 5) {
        $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$room_id'");
        echo "POWER_OFF";
        log_access($user_id, $rfid, $room_id, null, 'Exit', 'granted', $device_type);
        exit();
    }
}

// --- PERMISSION CHECK (Policy Table) ---
$policy = $conn->query("SELECT * FROM access_policies WHERE role = '$u_role' AND (device_type = '$device_type' OR device_type = '*') LIMIT 1")->fetch_assoc();
$is_auth = false; $sched_id = "NULL";

if ($policy) {
    if ($policy['requires_schedule'] == 1) {
        $sched = check_schedule($user_id, $room_id, $user['CourseSection_id']);
        if ($sched) { $is_auth = true; $sched_id = $sched['Schedule_id']; }
    } else { $is_auth = true; }
}

// 6. EXECUTE ACCESS
if ($is_auth) {
    log_access($user_id, $rfid, $room_id, $sched_id, $access_type, 'granted', $device_type);
    if ($device_type == 'POWER') {
        if ($access_type == 'Entry') {
            $conn->query("UPDATE classrooms SET Status = 'Occupied' WHERE Room_id = '$room_id'");
            echo "POWER_ON";
        } else {
            $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$room_id'");
            echo "POWER_OFF";
        }
    } else { echo "GRANTED"; }
} else {
    echo "DENIED";
    log_access($user_id, $rfid, $room_id, null, $access_type, 'denied', $device_type);
}

// --- FUNCTIONS ---

function check_schedule($user_id, $room_id, $course_section_id) {
    global $conn; $day = date('D'); $time = date('H:i:s');
    
    // 1. Faculty Check
    $faculty_res = $conn->query("SELECT Schedule_id FROM schedule WHERE Room_id = '$room_id' AND Faculty_id = '$user_id' AND Day = '$day' AND '$time' BETWEEN Start_time AND End_time LIMIT 1");
    if ($faculty_res && $faculty_res->num_rows > 0) return $faculty_res->fetch_assoc();

    // 2. Individual Permission Check
    $special_res = $conn->query("SELECT s.Schedule_id FROM schedule s JOIN individual_permissions ip ON s.Schedule_id = ip.Schedule_id WHERE s.Room_id = '$room_id' AND ip.User_id = '$user_id' AND s.Day = '$day' AND '$time' BETWEEN s.Start_time AND s.End_time LIMIT 1");
    if ($special_res && $special_res->num_rows > 0) return $special_res->fetch_assoc();

    // 3. Regular Student Check
    if (!empty($course_section_id) && $course_section_id !== "NULL") {
        $student_res = $conn->query("SELECT s.Schedule_id FROM schedule s JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id WHERE s.Room_id = '$room_id' AND s.Day = '$day' AND sa.CourseSection_id = '$course_section_id' AND '$time' BETWEEN s.Start_time AND s.End_time LIMIT 1");
        return ($student_res && $student_res->num_rows > 0) ? $student_res->fetch_assoc() : false;
    }
    return false;
}

function log_access($u, $r, $rm, $s, $t, $st, $dt = null) {
    global $conn;
    $u_safe = ($u && $u !== "NULL") ? "'$u'" : "NULL";
    $s_safe = ($s && $s !== "NULL") ? "'$s'" : "NULL";
    $dt_safe = $dt ? "'$dt'" : "NULL";
    $conn->query("INSERT INTO access_log (User_id, Rfid_tag, Room_id, Schedule_id, Access_time, Access_type, device_type, Status) VALUES ($u_safe, '$r', '$rm', $s_safe, NOW(), '$t', $dt_safe, '$st')");
}
?>