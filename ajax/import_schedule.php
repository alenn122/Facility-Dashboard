<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/import_debug.log');

// If you have Composer autoload
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Manual includes for PhpSpreadsheet
    require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/IOFactory.php';
    require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/Shared/Date.php';
    require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
    require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/Worksheet/Worksheet.php';
    // Add more includes as needed
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// ob_start();

$response = ['success' => false, 'message' => '', 'data' => []];

try {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'facility-dashboard');
    
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    // Check if file was uploaded
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error occurred');
    }
    
    $file = $_FILES['excel_file']['tmp_name'];
    $action = $_POST['action'] ?? 'validate';
    $skipFirstRow = isset($_POST['skip_first_row']) && $_POST['skip_first_row'] === 'true';
    $updateExisting = isset($_POST['update_existing']) && $_POST['update_existing'] === 'true';
    
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    if (empty($rows)) {
        throw new Exception('Excel file is empty');
    }
    
    // Remove header row if needed
    if ($skipFirstRow) {
        array_shift($rows);
    }
    
    $imported = 0;
    $skipped = 0;
    $failed = 0;
    $errors = [];
    $validRows = [];
    $rowNumber = $skipFirstRow ? 2 : 1;
    
    foreach ($rows as $row) {
        // Skip empty rows
        if (empty(array_filter($row))) {
            $rowNumber++;
            continue;
        }
        
        // Expected columns
        $subjectCode = trim($row[0] ?? '');
        $courseSection = trim($row[1] ?? '');
        $day = trim($row[2] ?? '');
        $startTime = trim($row[3] ?? '');
        $endTime = trim($row[4] ?? '');
        $roomCode = trim($row[5] ?? '');
        $facultyEmail = trim($row[6] ?? '');
        
        // Validate required fields
        $rowErrors = [];
        
        if (empty($subjectCode)) {
            $rowErrors[] = 'Subject Code is required';
        }
        
        if (empty($courseSection)) {
            $rowErrors[] = 'Course Section is required';
        }
        
        if (empty($day)) {
            $rowErrors[] = 'Day is required';
        } elseif (!in_array($day, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])) {
            $rowErrors[] = 'Invalid day format. Use Mon, Tue, Wed, Thu, Fri, Sat, Sun';
        }
        
        if (empty($startTime)) {
            $rowErrors[] = 'Start Time is required';
        }
        
        if (empty($endTime)) {
            $rowErrors[] = 'End Time is required';
        }
        
        if (empty($roomCode)) {
            $rowErrors[] = 'Room Code is required';
        }
        
        if (empty($facultyEmail)) {
            $rowErrors[] = 'Faculty Email is required';
        }
        
        $subjectId = null;
        $courseSectionId = null;
        $roomId = null;
        $facultyId = null;
        $facultyName = '';
        
        // If no validation errors, check database references
        if (empty($rowErrors)) {
            // Get Subject ID
            $stmt = $conn->prepare("SELECT Subject_id FROM subject WHERE Code = ?");
            $stmt->bind_param('s', $subjectCode);
            $stmt->execute();
            $stmt->bind_result($subjectId);
            $stmt->fetch();
            $stmt->close();
            
            if (!$subjectId) {
                $rowErrors[] = "Subject '$subjectCode' not found";
            }
            
            // Get Course Section ID
            $stmt = $conn->prepare("SELECT CourseSection_id FROM course_section WHERE CourseSection = ?");
            $stmt->bind_param('s', $courseSection);
            $stmt->execute();
            $stmt->bind_result($courseSectionId);
            $stmt->fetch();
            $stmt->close();
            
            if (!$courseSectionId) {
                $rowErrors[] = "Course Section '$courseSection' not found";
            }
            
            // Get Room ID
            $stmt = $conn->prepare("SELECT Room_id FROM classrooms WHERE Room_code = ?");
            $stmt->bind_param('s', $roomCode);
            $stmt->execute();
            $stmt->bind_result($roomId);
            $stmt->fetch();
            $stmt->close();
            
            if (!$roomId) {
                $rowErrors[] = "Room '$roomCode' not found";
            }
            
            // Get Faculty ID
            $stmt = $conn->prepare("SELECT User_id, CONCAT(F_name, ' ', L_name) FROM users WHERE Email = ? AND Role IN ('Faculty', 'Admin')");
            $stmt->bind_param('s', $facultyEmail);
            $stmt->execute();
            $stmt->bind_result($facultyId, $facultyName);
            $stmt->fetch();
            $stmt->close();
            
            if (!$facultyId) {
                $rowErrors[] = "Faculty with email '$facultyEmail' not found";
            }
            
            // Format times
            if (!empty($startTime) && strpos($startTime, ':') === false) {
                // Convert Excel time if needed
                if (is_numeric($startTime)) {
                    try {
                        $startTime = Date::excelToDateTimeObject($startTime)->format('H:i');
                    } catch (Exception $e) {
                        $rowErrors[] = 'Invalid start time format';
                    }
                }
            }
            
            if (!empty($endTime) && strpos($endTime, ':') === false) {
                if (is_numeric($endTime)) {
                    try {
                        $endTime = Date::excelToDateTimeObject($endTime)->format('H:i');
                    } catch (Exception $e) {
                        $rowErrors[] = 'Invalid end time format';
                    }
                }
            }
            
            // Validate time format
            if (!empty($startTime) && !preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $startTime)) {
                $rowErrors[] = 'Invalid start time format. Use HH:MM (24-hour)';
            }
            
            if (!empty($endTime) && !preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $endTime)) {
                $rowErrors[] = 'Invalid end time format. Use HH:MM (24-hour)';
            }
            
            // Validate time order
            if (!empty($startTime) && !empty($endTime) && $startTime >= $endTime) {
                $rowErrors[] = 'End time must be after start time';
            }
        }
        
        $isValid = empty($rowErrors);
        
        // Prepare row data for response
        $rowData = [
            'row' => $rowNumber,
            'subject_code' => $subjectCode,
            'course_section' => $courseSection,
            'day' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room_code' => $roomCode,
            'faculty_email' => $facultyEmail,
            'errors' => $rowErrors,
            'valid' => $isValid
        ];
        
        $response['data'][] = $rowData;
        
        // If valid and action is import, insert/update
        if ($isValid && $action === 'import') {
            $conn->begin_transaction();
            try {
                // Check if schedule already exists
                $stmt = $conn->prepare("
                    SELECT s.Schedule_id 
                    FROM schedule s
                    INNER JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id
                    WHERE s.Subject_id = ? 
                    AND sa.CourseSection_id = ?
                    AND s.Day = ?
                    AND s.Start_time = ?
                    AND s.End_time = ?
                ");
                $stmt->bind_param('iisss', $subjectId, $courseSectionId, $day, $startTime, $endTime);
                $stmt->execute();
                $stmt->bind_result($existingId);
                $stmt->fetch();
                $stmt->close();
                
                if ($existingId && $updateExisting) {
                    // Update existing
                    $stmt = $conn->prepare("
                        UPDATE schedule 
                        SET Room_id = ?, Faculty_id = ?
                        WHERE Schedule_id = ?
                    ");
                    $stmt->bind_param('iii', $roomId, $facultyId, $existingId);
                    $stmt->execute();
                    $scheduleId = $existingId;
                    $imported++;
                    
                } elseif (!$existingId) {
                    // Insert new
                    $stmt = $conn->prepare("
                        INSERT INTO schedule (Subject_id, Room_id, Faculty_id, Day, Start_time, End_time) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param('iiisss', $subjectId, $roomId, $facultyId, $day, $startTime, $endTime);
                    $stmt->execute();
                    $scheduleId = $stmt->insert_id;
                    
                    // Insert into schedule_access
                    $stmt = $conn->prepare("INSERT INTO schedule_access (Schedule_id, CourseSection_id) VALUES (?, ?)");
                    $stmt->bind_param('ii', $scheduleId, $courseSectionId);
                    $stmt->execute();
                    
                    $imported++;
                } else {
                    $skipped++;
                }
                
                $conn->commit();
                
            } catch (Exception $e) {
                $conn->rollback();
                $failed++;
                error_log("Import error for row $rowNumber: " . $e->getMessage());
            }
        }
        
        $rowNumber++;
    }
    
    $conn->close();
    
    // ob_end_clean();
    
    if ($action === 'import') {
        echo json_encode([
            'success' => true,
            'message' => "Import completed! Imported: $imported, Skipped: $skipped, Failed: $failed",
            'imported' => $imported,
            'skipped' => $skipped,
            'failed' => $failed,
            'data' => $response['data']
        ]);
    } else {
        // Validation only
        $validCount = count(array_filter($response['data'], function($row) { return $row['valid']; }));
        $invalidCount = count($response['data']) - $validCount;
        
        echo json_encode([
            'success' => true,
            'message' => "Validation completed. Valid rows: $validCount, Invalid rows: $invalidCount",
            'data' => $response['data']
        ]);
    }
    
} catch (Exception $e) {
    // ob_end_clean();
    error_log("Import exception: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>