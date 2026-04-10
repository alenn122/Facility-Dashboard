<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

include 'conn.php';
include "session_auth.php";

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

switch ($action) {
    case 'get_users':
        getUsers($conn);
        break;
    
    case 'save_user':
        saveUser($conn);
        break;
    
    case 'delete_user':
        deleteUser($conn);
        break;
    
    case 'process_import':
        processImport($conn);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
        break;
}

$conn->close();

function getUsers($conn) {
    $sql = "SELECT u.*, cs.CourseSection, u.courseSection_id as cs_id 
            FROM users u 
            LEFT JOIN course_section cs ON u.courseSection_id = cs.CourseSection_id 
            ORDER BY u.User_id DESC";
    $result = mysqli_query($conn, $sql);
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
}

function saveUser($conn) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $id = !empty($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $rfid = trim($_POST['rfid_tag'] ?? '');
    $fname = trim($_POST['f_name'] ?? '');
    $lname = trim($_POST['l_name'] ?? '');
    $role = $_POST['role'] ?? '';
    $status = $_POST['status'] ?? 'Active';
    $courseId = !empty($_POST['course_id']) ? intval($_POST['course_id']) : null;
    
    if (empty($rfid) || empty($fname) || empty($lname) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        return;
    }
    
    $validRoles = ['Student', 'Faculty', 'Admin', 'Cleaning', 'Security'];
    if (!in_array($role, $validRoles)) {
        echo json_encode(['success' => false, 'message' => 'Invalid role selected']);
        return;
    }
    
    if ($id > 0) {
        $sql = "UPDATE users SET Rfid_tag=?, F_name=?, L_name=?, Role=?, Status=?, courseSection_id=? WHERE User_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssii", $rfid, $fname, $lname, $role, $status, $courseId, $id);
    } else {
        $sql = "INSERT INTO users (Rfid_tag, F_name, L_name, Role, Status, courseSection_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $rfid, $fname, $lname, $role, $status, $courseId);
    }
    
    $res = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    if ($res) {
        echo json_encode(['success' => true, 'message' => $id > 0 ? 'User updated successfully!' : 'User added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
}

function deleteUser($conn) {
    $id = intval($_POST['user_id'] ?? 0);
    
    $user_check = mysqli_query($conn, "SELECT Status FROM users WHERE User_id=$id");
    $user_data = mysqli_fetch_assoc($user_check);
    
    if ($user_data && $user_data['Status'] === 'Inactive') {
        $res = mysqli_query($conn, "DELETE FROM users WHERE User_id=$id");
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User must be inactive before deletion.']);
    }
}

function processImport($conn) {
    set_time_limit(300);
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $importData = json_decode($_POST['import_data'], true);
    $updateExisting = isset($_POST['update_existing']) && $_POST['update_existing'] == '1';
    $autoCreateMissing = isset($_POST['auto_create_missing']) && $_POST['auto_create_missing'] == '1';
    
    if (empty($importData)) {
        echo json_encode(['success' => false, 'message' => 'No data to import']);
        return;
    }
    
    // Get existing data
    $existingRfids = [];
    $existingQuery = mysqli_query($conn, "SELECT User_id, Rfid_tag FROM users");
    while ($row = mysqli_fetch_assoc($existingQuery)) {
        $existingRfids[$row['Rfid_tag']] = $row['User_id'];
    }
    
    // Get course sections
    $courseSections = [];
    $courseQuery = mysqli_query($conn, "SELECT CourseSection_id, CourseSection FROM course_section");
    while ($course = mysqli_fetch_assoc($courseQuery)) {
        $courseSections[strtolower(trim($course['CourseSection']))] = $course['CourseSection_id'];
    }
    
    $validRoles = ['Student', 'Faculty', 'Admin', 'Cleaning', 'Security'];
    $validStatus = ['Active', 'Inactive'];
    
    $successCount = 0;
    $errorCount = 0;
    $updateCount = 0;
    $errors = [];
    
    mysqli_begin_transaction($conn);
    
    try {
        foreach ($importData as $index => $row) {
            $rowNum = $index + 2;
            $rfid = trim($row['rfid_tag'] ?? '');
            $fname = trim($row['f_name'] ?? '');
            $lname = trim($row['l_name'] ?? '');
            $role = trim($row['role'] ?? '');
            $status = trim($row['status'] ?? 'Active');
            $courseSection = trim($row['course_section'] ?? '');
            
            $rowErrors = [];
            
            if (empty($rfid)) $rowErrors[] = "RFID required";
            if (empty($fname)) $rowErrors[] = "First name required";
            if (empty($lname)) $rowErrors[] = "Last name required";
            if (empty($role)) $rowErrors[] = "Role required";
            if (!in_array($role, $validRoles)) $rowErrors[] = "Invalid role";
            if (!in_array($status, $validStatus)) $rowErrors[] = "Invalid status";
            
            $isUpdate = isset($existingRfids[$rfid]);
            if ($isUpdate && !$updateExisting) {
                $rowErrors[] = "RFID already exists";
            }
            
            $courseId = null;
            if ($role === 'Student' && !empty($courseSection)) {
                $courseKey = strtolower($courseSection);
                if (isset($courseSections[$courseKey])) {
                    $courseId = $courseSections[$courseKey];
                } elseif ($autoCreateMissing) {
                    $insertCourse = mysqli_prepare($conn, "INSERT INTO course_section (CourseSection) VALUES (?)");
                    mysqli_stmt_bind_param($insertCourse, "s", $courseSection);
                    if (mysqli_stmt_execute($insertCourse)) {
                        $courseId = mysqli_insert_id($conn);
                        $courseSections[$courseKey] = $courseId;
                    }
                    mysqli_stmt_close($insertCourse);
                } else {
                    $rowErrors[] = "Course section '$courseSection' not found";
                }
            } elseif ($role === 'Student' && empty($courseSection)) {
                $rowErrors[] = "Course section required for students";
            }
            
            if (!empty($rowErrors)) {
                $errorCount++;
                $errors[] = "Row $rowNum: " . implode(", ", $rowErrors);
                continue;
            }
            
            if ($isUpdate && $updateExisting) {
                $userId = $existingRfids[$rfid];
                $stmt = mysqli_prepare($conn, "UPDATE users SET F_name=?, L_name=?, Role=?, Status=?, courseSection_id=? WHERE User_id=?");
                mysqli_stmt_bind_param($stmt, "ssssii", $fname, $lname, $role, $status, $courseId, $userId);
                if (mysqli_stmt_execute($stmt)) $updateCount++;
                else $errorCount++;
                mysqli_stmt_close($stmt);
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO users (Rfid_tag, F_name, L_name, Role, Status, courseSection_id) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssssi", $rfid, $fname, $lname, $role, $status, $courseId);
                if (mysqli_stmt_execute($stmt)) $successCount++;
                else $errorCount++;
                mysqli_stmt_close($stmt);
            }
        }
        
        if ($errorCount > 0) {
            mysqli_rollback($conn);
            echo json_encode([
                'success' => false,
                'message' => "Import completed with errors: {$successCount} added, {$updateCount} updated, {$errorCount} errors.",
                'stats' => ['added' => $successCount, 'updated' => $updateCount, 'errors' => $errorCount],
                'error_details' => $errors
            ]);
        } else {
            mysqli_commit($conn);
            echo json_encode([
                'success' => true,
                'message' => "Import successful: {$successCount} users added, {$updateCount} users updated.",
                'stats' => ['added' => $successCount, 'updated' => $updateCount, 'errors' => 0]
            ]);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>