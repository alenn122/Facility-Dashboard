<?php
date_default_timezone_set('Asia/Manila');
// CONFIGURATION
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "facility-dashboard";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// --------------------------------------------------------------------
// 2. THE "HEARTBEAT" (Receive Ping from ESP32) 
// --------------------------------------------------------------------
// --------------------------------------------------------------------
// 2. THE "HEARTBEAT" (Receive Ping from ESP32) 
// --------------------------------------------------------------------
if (isset($_POST['ping'])) {
    $mac = $_POST['mac'];
    $conn->query("UPDATE devices SET last_seen = NOW(), status = 'Online' WHERE mac_address = '$mac'");
    
    $res = $conn->query("SELECT d.room_id, c.Status FROM devices d JOIN classrooms c ON d.room_id = c.Room_id WHERE d.mac_address = '$mac' LIMIT 1");
    
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $rid = $row['room_id'];

        if ($row['Status'] == 'Occupied') {
            // --- NEW: ADMIN OVERRIDE CHECK ---
            // Find the role of the last person who entered this room
            $last_user_query = $conn->query("SELECT u.Role FROM access_log l 
                                             JOIN users u ON l.User_id = u.User_id 
                                             WHERE l.Room_id = '$rid' AND l.Status = 'granted' AND l.Access_type = 'Entry' 
                                             ORDER BY l.Access_time DESC LIMIT 1");
            
            if ($last_user_query && $last_user_query->num_rows > 0) {
                $last_user = $last_user_query->fetch_assoc();
                // If an Admin is inside, ignore the schedule and stay ON
                if ($last_user['Role'] == 'Admin') {
                    echo "PONG";
                    exit();
                }
            }
            // --- END ADMIN OVERRIDE ---

            $day = date('D'); 
            $time = date('H:i:s');
            
            $sched = $conn->query("SELECT End_time FROM schedule WHERE Room_id = '$rid' AND Day = '$day' AND '$time' BETWEEN Start_time AND End_time LIMIT 1");

            if ($sched->num_rows > 0) {
                $s = $sched->fetch_assoc();
                $seconds_left = strtotime($s['End_time']) - strtotime($time);

                if ($seconds_left > 0 && $seconds_left <= 300) {
                    echo "WARNING_5MIN";
                    exit();
                }
            } else {
                $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$rid'");
                echo "FORCE_OFF"; 
                exit();
            }
        }
    }
    echo "PONG";
    exit();
}

// 1. RECEIVE DATA
$mac  = isset($_POST['mac']) ? $_POST['mac'] : ''; 
$rfid = isset($_POST['rfid']) ? $_POST['rfid'] : '';

// 2. DEVICE & ROOM LOOKUP
$device_sql = "SELECT d.room_id, d.device_type FROM devices d WHERE d.mac_address = '$mac' LIMIT 1";
$device_query = $conn->query($device_sql);

// Fix: Check if a device exists BEFORE trying to fetch the array
if ($device_query && $device_query->num_rows > 0) {
    $device = $device_query->fetch_assoc();
    $room_id = $device['room_id'];
    $device_type = strtoupper($device['device_type']);
} else {
    echo "UNKNOWN DEVICE"; 
    exit();
}

// 3. USER LOOKUP
$user_query = $conn->query("SELECT * FROM users WHERE Rfid_tag='$rfid' AND Status='Active'");
if ($user_query->num_rows == 0) {
    echo "DENIED"; 
    log_access(null, $rfid, $room_id, null, 'Entry', 'denied', $device_type);
    exit();
}
$user = $user_query->fetch_assoc();
$user_id = $user['User_id'];

// 4. SMART ENTRY/EXIT TOGGLE
$last_granted_query = $conn->query("SELECT Access_type FROM access_log 
                                    WHERE User_id = '$user_id' AND Room_id = '$room_id' AND Status = 'granted'
                                    ORDER BY Access_time DESC LIMIT 1");

$access_type = 'Entry'; 
if ($last_granted_query->num_rows > 0) {
    $last_granted = $last_granted_query->fetch_assoc();
    if ($last_granted['Access_type'] == 'Entry') {
        $access_type = 'Exit';
    }
}

// 5. PERMISSION CHECK
$is_authorized = false;
$sched_id = "NULL";

if ($user['Role'] == 'Admin') {
    // Admins (Maintenance/IT) have access to EVERYTHING at ANY TIME
    $is_authorized = true;
} 
else if ($user['Role'] == 'Faculty') {
    // Faculty must follow the schedule for BOTH Door and Power
    $schedule = check_schedule($user_id, $room_id, $user['CourseSection_id']);
    if ($schedule) {
        $is_authorized = true;
        $sched_id = $schedule['Schedule_id'];
    }
} 
else if ($user['Role'] == 'Student') {
    // Students ONLY get access if the device is a DOOR and they have a schedule
    if ($device_type == 'DOOR') {
        $schedule = check_schedule($user_id, $room_id, $user['CourseSection_id']);
        if ($schedule) {
            $is_authorized = true;
            $sched_id = $schedule['Schedule_id'];
        }
    } else {
        // Students are NEVER authorized for 'POWER' devices
        $is_authorized = false;
    }
}

// 6. EXECUTE ACCESS & UPDATE ROOM STATUS
if ($is_authorized) {
    log_access($user_id, $rfid, $room_id, $sched_id, $access_type, 'granted', $device_type);
    
    if ($device_type == 'DOOR') {
        // Only open the door, don't touch the room status/power here 
        // unless you want the first person in to trigger the lights
        echo "POWER_ON"; 
    } 
    else if ($device_type == 'POWER') {
        if ($access_type == 'Entry') {
            $conn->query("UPDATE classrooms SET Status = 'Occupied' WHERE Room_id = '$room_id'");
            echo "POWER_ON";
        } else {
            $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$room_id'");
            echo "POWER_OFF";
        }
    }
} else {
    echo "DENIED";
    log_access($user_id, $rfid, $room_id, null, $access_type, 'denied', $device_type);
}

// --- FUNCTIONS ---

function check_schedule($user_id, $room_id, $course_section_id) {
    global $conn;
    $day = date('D'); 
    $time = date('H:i:s');

    // 1. First, check if the user is the FACULTY assigned to this room right now
    $faculty_sql = "SELECT Schedule_id FROM schedule 
                    WHERE Room_id = '$room_id' 
                    AND Faculty_id = '$user_id' 
                    AND Day = '$day'
                    AND '$time' BETWEEN Start_time AND End_time LIMIT 1";
    
    $faculty_res = $conn->query($faculty_sql);
    if ($faculty_res && $faculty_res->num_rows > 0) {
        return $faculty_res->fetch_assoc();
    }

    // 2. If not faculty, check if it's a STUDENT assigned via CourseSection
    if (!empty($course_section_id) && $course_section_id !== "NULL") {
        $student_sql = "SELECT s.Schedule_id FROM schedule s
                        JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id
                        WHERE s.Room_id = '$room_id' 
                        AND s.Day = '$day'
                        AND sa.CourseSection_id = '$course_section_id'
                        AND '$time' BETWEEN s.Start_time AND s.End_time LIMIT 1";
        $student_res = $conn->query($student_sql);
        return ($student_res && $student_res->num_rows > 0) ? $student_res->fetch_assoc() : false;
    }

    return false;
}


// Update the function definition to include $dt (Device Type)
function log_access($u, $r, $rm, $s, $t, $st, $dt = null) {
    global $conn;

    $u_safe = (!empty($u) && $u !== "NULL") ? "'$u'" : "NULL";
    $s_safe = (!empty($s) && $s !== "NULL") ? "'$s'" : "NULL";
    // Sanitize the device type
    $dt_safe = !empty($dt) ? "'$dt'" : "NULL";

    // Update the INSERT statement to include device_type
    $sql = "INSERT INTO access_log (User_id, Rfid_tag, Room_id, Schedule_id, Access_time, Access_type, device_type, Status) 
            VALUES ($u_safe, '$r', '$rm', $s_safe, NOW(), '$t', $dt_safe, '$st')";

    if (!$conn->query($sql)) {
        echo "LOGGING ERROR: " . $conn->error;
    }
}