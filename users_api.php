<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

ob_start();

include 'conn.php';
include "session_auth.php";

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// Log the action for debugging
error_log("API Action: " . $action);

switch ($action) {
    case 'get_users':
        getUsers($conn);
        break;
    
    case 'save_user':
        saveUser($conn);
        break;
    
    case 'archive_user':
        archiveUser($conn);
        break;
    
    case 'bulk_archive':
        bulkArchive($conn);
        break;
    
    case 'get_archived_users':
        getArchivedUsers($conn);
        break;
    
    case 'get_archived_count':
        getArchivedCount($conn);
        break;
    
    case 'restore_user':
        restoreUser($conn);
        break;
    
    case 'permanent_delete_user':
        permanentDeleteUser($conn);
        break;
    
    case 'process_import':
        processImport($conn);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
        break;
}

ob_end_flush();
$conn->close();

function sendResponse($data) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    echo json_encode($data);
    exit();
}

/**
 * Get all NON-ARCHIVED users (Active and Inactive only)
 */
function getUsers($conn) {
    $sql = "SELECT u.*, cs.CourseSection, u.courseSection_id as cs_id 
            FROM users u 
            LEFT JOIN course_section cs ON u.courseSection_id = cs.CourseSection_id 
            WHERE u.Status != 'Archived' AND u.Status IS NOT NULL
            ORDER BY u.User_id DESC";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        sendResponse(['success' => false, 'message' => 'Database query failed: ' . mysqli_error($conn)]);
        return;
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    sendResponse(['success' => true, 'users' => $users]);
}

/**
 * Archive a single user - THIS IS THE DELETE FUNCTION
 */
function archiveUser($conn) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $user_id = intval($_POST['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        sendResponse(['success' => false, 'message' => 'Invalid user ID']);
        return;
    }
    
    // Prevent self-archiving
    if (isset($_SESSION['user_id']) && $user_id == $_SESSION['user_id']) {
        sendResponse(['success' => false, 'message' => 'You cannot archive your own account']);
        return;
    }
    
    // First, check if user exists
    $checkSql = "SELECT User_id, Status FROM users WHERE User_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "i", $user_id);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($checkStmt);
    
    if (!$user) {
        sendResponse(['success' => false, 'message' => 'User not found']);
        return;
    }
    
    if ($user['Status'] === 'Archived') {
        sendResponse(['success' => false, 'message' => 'User is already archived']);
        return;
    }
    
    // Update user status to Archived
    $query = "UPDATE users SET Status = 'Archived', archived_date = NOW() WHERE User_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $affected = mysqli_stmt_affected_rows($stmt);
        error_log("Archive user $user_id: affected rows = $affected");
        
        if ($affected > 0) {
            sendResponse(['success' => true, 'message' => 'User archived successfully']);
        } else {
            sendResponse(['success' => false, 'message' => 'Failed to archive user - no rows affected']);
        }
    } else {
        sendResponse(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt);
}

/**
 * Bulk archive multiple users
 */
function bulkArchive($conn) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $user_ids = json_decode($_POST['user_ids'], true);
    
    if (empty($user_ids) || !is_array($user_ids)) {
        sendResponse(['success' => false, 'message' => 'No users selected']);
        return;
    }
    
    // Prevent self-archiving
    $current_user_id = $_SESSION['user_id'] ?? 0;
    if (in_array($current_user_id, $user_ids)) {
        sendResponse(['success' => false, 'message' => 'You cannot archive your own account']);
        return;
    }
    
    $success_count = 0;
    $failed_count = 0;
    
    foreach ($user_ids as $user_id) {
        $query = "UPDATE users SET Status = 'Archived', archived_date = NOW() WHERE User_id = ? AND Status != 'Archived'";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            $success_count++;
        } else {
            $failed_count++;
        }
        mysqli_stmt_close($stmt);
    }
    
    sendResponse(['success' => true, 'message' => "$success_count user(s) archived successfully, $failed_count failed"]);
}

/**
 * Get count of archived users
 */
function getArchivedCount($conn) {
    $query = "SELECT COUNT(*) as count FROM users WHERE Status = 'Archived'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        sendResponse(['success' => true, 'count' => (int)$row['count']]);
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to get count']);
    }
}

/**
 * Get all archived users
 */
function getArchivedUsers($conn) {
    $query = "SELECT u.*, cs.CourseSection 
              FROM users u 
              LEFT JOIN course_section cs ON u.courseSection_id = cs.CourseSection_id 
              WHERE u.Status = 'Archived' 
              ORDER BY u.archived_date DESC";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        sendResponse(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        return;
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    sendResponse(['success' => true, 'users' => $users]);
}

/**
 * Restore an archived user
 */
function restoreUser($conn) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $user_id = intval($_POST['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        sendResponse(['success' => false, 'message' => 'Invalid user ID']);
        return;
    }
    
    $query = "UPDATE users SET Status = 'Active', archived_date = NULL WHERE User_id = ? AND Status = 'Archived'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            sendResponse(['success' => true, 'message' => 'User restored successfully']);
        } else {
            sendResponse(['success' => false, 'message' => 'User not found or not archived']);
        }
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to restore user']);
    }
    mysqli_stmt_close($stmt);
}

/**
 * Permanently delete an archived user
 */
function permanentDeleteUser($conn) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $user_id = intval($_POST['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        sendResponse(['success' => false, 'message' => 'Invalid user ID']);
        return;
    }
    
    // Prevent self-deletion
    if (isset($_SESSION['user_id']) && $user_id == $_SESSION['user_id']) {
        sendResponse(['success' => false, 'message' => 'You cannot delete your own account']);
        return;
    }
    
    $query = "DELETE FROM users WHERE User_id = ? AND Status = 'Archived'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            sendResponse(['success' => true, 'message' => 'User permanently deleted']);
        } else {
            sendResponse(['success' => false, 'message' => 'User not found or not archived']);
        }
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to delete user']);
    }
    mysqli_stmt_close($stmt);
}

/**
 * Save user
 */
function saveUser($conn) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
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
        sendResponse(['success' => false, 'message' => 'Please fill in all required fields']);
        return;
    }
    
    $validRoles = ['Student', 'Faculty', 'Admin', 'Cleaning', 'Security'];
    if (!in_array($role, $validRoles)) {
        sendResponse(['success' => false, 'message' => 'Invalid role selected']);
        return;
    }
    
    if ($id == 0) {
        $checkSql = "SELECT User_id FROM users WHERE Rfid_tag = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "s", $rfid);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            mysqli_stmt_close($checkStmt);
            sendResponse(['success' => false, 'message' => 'RFID tag already exists']);
            return;
        }
        mysqli_stmt_close($checkStmt);
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
        sendResponse(['success' => true, 'message' => $id > 0 ? 'User updated successfully!' : 'User added successfully!']);
    } else {
        sendResponse(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
}

/**
 * Process Excel import
 */
function processImport($conn) {
    set_time_limit(300);
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        sendResponse(['success' => false, 'message' => 'CSRF token validation failed']);
        return;
    }
    
    $importData = json_decode($_POST['import_data'], true);
    $updateExisting = isset($_POST['update_existing']) && $_POST['update_existing'] == '1';
    $autoCreateMissing = isset($_POST['auto_create_missing']) && $_POST['auto_create_missing'] == '1';
    
    if (empty($importData)) {
        sendResponse(['success' => false, 'message' => 'No data to import']);
        return;
    }
    
    $existingRfids = [];
    $existingQuery = mysqli_query($conn, "SELECT User_id, Rfid_tag FROM users");
    while ($row = mysqli_fetch_assoc($existingQuery)) {
        $existingRfids[$row['Rfid_tag']] = $row['User_id'];
    }
    
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
            if (!in_array($role, $validRoles)) $rowErrors[] = "Invalid role: $role";
            if (!empty($status) && !in_array($status, $validStatus)) $rowErrors[] = "Invalid status: $status";
            
            $isUpdate = isset($existingRfids[$rfid]);
            if ($isUpdate && !$updateExisting) {
                $rowErrors[] = "RFID already exists and update not enabled";
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
                    } else {
                        $rowErrors[] = "Failed to create course section: $courseSection";
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
                if (mysqli_stmt_execute($stmt)) {
                    $updateCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Row $rowNum: Update failed";
                }
                mysqli_stmt_close($stmt);
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO users (Rfid_tag, F_name, L_name, Role, Status, courseSection_id) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssssi", $rfid, $fname, $lname, $role, $status, $courseId);
                if (mysqli_stmt_execute($stmt)) {
                    $successCount++;
                    $existingRfids[$rfid] = mysqli_insert_id($conn);
                } else {
                    $errorCount++;
                    $errors[] = "Row $rowNum: Insert failed";
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        if ($errorCount > 0) {
            mysqli_rollback($conn);
            sendResponse([
                'success' => false,
                'message' => "Import completed with errors: {$successCount} added, {$updateCount} updated, {$errorCount} errors.",
                'error_details' => $errors
            ]);
        } else {
            mysqli_commit($conn);
            sendResponse([
                'success' => true,
                'message' => "Import successful: {$successCount} users added, {$updateCount} users updated."
            ]);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        sendResponse(['success' => false, 'message' => 'Import error: ' . $e->getMessage()]);
    }
}
?>