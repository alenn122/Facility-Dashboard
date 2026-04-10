<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporarily enable for debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/schedule_debug.log');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facility-dashboard";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$conn->set_charset("utf8mb4");

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';

switch ($action) {
    case 'get_schedules':
        getSchedules($conn);
        break;
    
    case 'get_schedule':
        getScheduleDetails($conn);
        break;
    
    case 'save_schedule':
        saveSchedule($conn);
        break;
    
    case 'delete_schedule':
        deleteSchedule($conn);
        break;

    case 'restore_schedule':
    $id = intval($_POST['schedule_id'] ?? 0);
    $stmt = $conn->prepare("UPDATE schedule SET is_deleted = 0 WHERE Schedule_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Schedule restored!']);
    }
    $stmt->close();
    break;
    
    case 'get_archived_schedules':
    $sql = "SELECT s.Schedule_id, sub.Code, sub.Description, s.Day, 
                   r.Room_code, CONCAT(u.F_name, ' ', u.L_name) as Faculty_name
            FROM schedule s
            INNER JOIN subject sub ON s.Subject_id = sub.Subject_id
            INNER JOIN classrooms r ON s.Room_id = r.Room_id
            INNER JOIN users u ON s.Faculty_id = u.User_id
            WHERE s.is_deleted = 1"; // Only archived items
    
    $result = $conn->query($sql);
    $archived = [];
    while($row = $result->fetch_assoc()) {
        $archived[] = $row;
    }
    echo json_encode(['success' => true, 'archived' => $archived]);
    break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
        break;
}

$conn->close();

function getSchedules($conn) {
    $course = isset($_GET['course']) && $_GET['course'] !== 'all' ? intval($_GET['course']) : 0;
    $day = isset($_GET['day']) && $_GET['day'] !== 'all' ? trim($_GET['day']) : '';
    $faculty = isset($_GET['faculty']) && $_GET['faculty'] !== 'all' ? intval($_GET['faculty']) : 0;
    $room = isset($_GET['room']) && $_GET['room'] !== 'all' ? intval($_GET['room']) : 0;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // First, check if there are any schedules at all
    $check_sql = "SELECT COUNT(*) as total FROM schedule";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    if ($check_row['total'] == 0) {
        echo json_encode(['success' => true, 'schedules' => []]);
        return;
    }

    $sql = "SELECT DISTINCT
                s.Schedule_id,
                s.Day,
                TIME_FORMAT(s.Start_time, '%H:%i') as Start_time,
                TIME_FORMAT(s.End_time, '%H:%i') as End_time,
                s.Subject_id,
                s.Room_id,
                s.Faculty_id,
                sub.Code,
                sub.Description,
                r.Room_code,
                CONCAT(u.F_name, ' ', u.L_name) AS Faculty_name,
                u.Status AS Faculty_Status,
                cs.CourseSection,
                cs.CourseSection_id
            FROM schedule s
            INNER JOIN subject sub ON s.Subject_id = sub.Subject_id
            INNER JOIN classrooms r ON s.Room_id = r.Room_id
            INNER JOIN users u ON s.Faculty_id = u.User_id
            LEFT JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id
            LEFT JOIN course_section cs ON sa.CourseSection_id = cs.CourseSection_id
            WHERE s.is_deleted = 0";

    $params = [];
    $types = '';

    if ($course > 0) {
        $sql .= " AND cs.CourseSection_id = ?";
        $params[] = $course;
        $types .= 'i';
    }
    if (!empty($day) && $day !== 'all') {
        $sql .= " AND s.Day = ?";
        $params[] = $day;
        $types .= 's';
    }
    if ($faculty > 0) {
        $sql .= " AND s.Faculty_id = ?";
        $params[] = $faculty;
        $types .= 'i';
    }
    if ($room > 0) {
        $sql .= " AND s.Room_id = ?";
        $params[] = $room;
        $types .= 'i';
    }
    if (!empty($search)) {
        $sql .= " AND (sub.Code LIKE ? OR sub.Description LIKE ? OR CONCAT(u.F_name, ' ', u.L_name) LIKE ? OR r.Room_code LIKE ? OR cs.CourseSection LIKE ?)";
        $searchTerm = "%" . $search . "%";
        for($i = 0; $i < 5; $i++) {
            $params[] = $searchTerm;
            $types .= 's';
        }
    }

    $sql .= " ORDER BY cs.CourseSection, FIELD(s.Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), s.Start_time";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $courseSectionId = $row['CourseSection_id'] ?: 'uncategorized';
        if (!isset($schedules[$courseSectionId])) {
            $schedules[$courseSectionId] = [];
        }
        $schedules[$courseSectionId][] = $row;
    }

    $stmt->close();
    echo json_encode(['success' => true, 'schedules' => $schedules]);
}

function getScheduleDetails($conn) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM schedule WHERE Schedule_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();

    if (!$schedule) {
        echo json_encode(['success' => false, 'message' => 'Schedule not found']);
        return;
    }

    $stmt = $conn->prepare("SELECT CourseSection_id FROM schedule_access WHERE Schedule_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $sections = [];
    while($r = $res->fetch_assoc()) {
        $sections[] = intval($r['CourseSection_id']);
    }
    $stmt->close();

    echo json_encode(['success' => true, 'schedule' => $schedule, 'course_sections' => $sections]);
}

function saveSchedule($conn) {
    $schedule_id = intval($_POST['schedule_id'] ?? 0);
    $subject_id = intval($_POST['subject'] ?? 0);
    $room_id = intval($_POST['room'] ?? 0);
    $faculty_id = intval($_POST['faculty'] ?? 0);
    $day = $_POST['day'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $course_sections = isset($_POST['course_sections']) ? $_POST['course_sections'] : [];

    if (!$subject_id || !$room_id || !$faculty_id || !$day || !$start_time || !$end_time || empty($course_sections)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    $conn->begin_transaction();
    try {
        if ($schedule_id > 0) {
            $stmt = $conn->prepare("UPDATE schedule SET Subject_id=?, Room_id=?, Faculty_id=?, Day=?, Start_time=?, End_time=? WHERE Schedule_id=?");
            $stmt->bind_param('iiisssi', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time, $schedule_id);
            $stmt->execute();
            $stmt->close();
            
            $stmt = $conn->prepare("DELETE FROM schedule_access WHERE Schedule_id=?");
            $stmt->bind_param('i', $schedule_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO schedule (Subject_id, Room_id, Faculty_id, Day, Start_time, End_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('iiisss', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time);
            $stmt->execute();
            $schedule_id = $stmt->insert_id;
            $stmt->close();
        }

        if (!empty($course_sections)) {
            $stmt = $conn->prepare("INSERT INTO schedule_access (Schedule_id, CourseSection_id) VALUES (?, ?)");
            foreach ($course_sections as $cs_id) {
                $cs_id = intval($cs_id);
                if ($cs_id > 0) {
                    $stmt->bind_param('ii', $schedule_id, $cs_id);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Schedule saved successfully!']);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function deleteSchedule($conn) {
    $id = intval($_POST['schedule_id'] ?? 0);
    
    // 1. Get Room ID before archiving
    $room_res = $conn->query("SELECT Room_id FROM schedule WHERE Schedule_id = $id");
    if (!$room_res || $room_res->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Schedule not found.']);
        return;
    }
    $room_id = $room_res->fetch_assoc()['Room_id'];

    // 2. Perform the Soft Delete (Archive)
    $stmt = $conn->prepare("UPDATE schedule SET is_deleted = 1 WHERE Schedule_id = ?");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        // 3. Check if any OTHER schedules are active in this room
        $day = date('D'); 
        $time = date('H:i:s');
        $active_check = $conn->query("SELECT Schedule_id FROM schedule 
                                    WHERE Room_id = '$room_id' 
                                    AND Day = '$day' 
                                    AND '$time' BETWEEN Start_time AND End_time 
                                    AND is_deleted = 0");

        if ($active_check->num_rows == 0) {
            // No other classes are running. 
            // Set a 1-minute delay before power off.
            $shutdown_time = date('Y-m-d H:i:s', strtotime('+1 minute'));
            $conn->query("UPDATE classrooms SET 
                          Status = 'Pending Shutdown', 
                          shutdown_at = '$shutdown_time' 
                          WHERE Room_id = '$room_id'");
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Schedule archived. Room power will shut down in 60 seconds.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Archive failed.']);
    }
}

?>