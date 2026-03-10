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
    $autoCreateMissing = isset($_POST['auto_create_missing']) && $_POST['auto_create_missing'] === 'true';
    
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
        
        // Expected columns: Code | Description | Course Section | Day | Start Time | End Time | Room Code | Faculty Name
        $subjectCode = trim($row[0] ?? '');
        $subjectDescription = trim($row[1] ?? '');
        $courseSection = trim($row[2] ?? '');
        $day = trim($row[3] ?? '');
        $startTime = trim($row[4] ?? '');
        $endTime = trim($row[5] ?? '');
        $roomCode = trim($row[6] ?? '');
        $facultyName = trim($row[7] ?? '');
        
        // If description is empty, use code as description
        if (empty($subjectDescription)) {
            $subjectDescription = $subjectCode;
        }
        
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
        } else {
            // Convert full day names to abbreviations
            $dayMap = [
                'Monday' => 'Mon',
                'Tuesday' => 'Tue',
                'Wednesday' => 'Wed',
                'Thursday' => 'Thu',
                'Friday' => 'Fri',
                'Saturday' => 'Sat',
                'Sunday' => 'Sun'
            ];
            
            // Check if it's a full day name and convert it
            if (isset($dayMap[$day])) {
                $day = $dayMap[$day];
            }
            
            // Validate the day format
            if (!in_array($day, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])) {
                $rowErrors[] = 'Invalid day format. Use Mon, Tue, Wed, Thu, Fri, Sat, Sun or full day names';
            }
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
        
        if (empty($facultyName)) {
            $rowErrors[] = 'Faculty Name is required';
        }
        
        $subjectId = null;
        $courseSectionId = null;
        $roomId = null;
        $facultyId = null;
        
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
                if ($autoCreateMissing && $action === 'import') {
                    // Auto-create subject with both code and description
                    $stmt = $conn->prepare("INSERT INTO subject (Code, Description) VALUES (?, ?)");
                    $stmt->bind_param('ss', $subjectCode, $subjectDescription);
                    $stmt->execute();
                    $subjectId = $stmt->insert_id;
                    $stmt->close();
                } else {
                    $rowErrors[] = "Subject '$subjectCode' not found";
                }
            }
            
            // Get Course Section ID
            $stmt = $conn->prepare("SELECT CourseSection_id FROM course_section WHERE CourseSection = ?");
            $stmt->bind_param('s', $courseSection);
            $stmt->execute();
            $stmt->bind_result($courseSectionId);
            $stmt->fetch();
            $stmt->close();
            
            if (!$courseSectionId) {
                if ($autoCreateMissing && $action === 'import') {
                    // Auto-create course section
                    $stmt = $conn->prepare("INSERT INTO course_section (CourseSection) VALUES (?)");
                    $stmt->bind_param('s', $courseSection);
                    $stmt->execute();
                    $courseSectionId = $stmt->insert_id;
                    $stmt->close();
                } else {
                    $rowErrors[] = "Course Section '$courseSection' not found";
                }
            }
            
            // Get Room ID
            $stmt = $conn->prepare("SELECT Room_id FROM classrooms WHERE Room_code = ?");
            $stmt->bind_param('s', $roomCode);
            $stmt->execute();
            $stmt->bind_result($roomId);
            $stmt->fetch();
            $stmt->close();
            
            if (!$roomId) {
                if ($autoCreateMissing && $action === 'import') {
                    // Auto-create room
                    $stmt = $conn->prepare("INSERT INTO classrooms (Room_code) VALUES (?)");
                    $stmt->bind_param('s', $roomCode);
                    $stmt->execute();
                    $roomId = $stmt->insert_id;
                    $stmt->close();
                } else {
                    $rowErrors[] = "Room '$roomCode' not found";
                }
            }
            
            // Get Faculty ID by name
            if (!empty($facultyName)) {
                // Try exact match first
                $stmt = $conn->prepare("SELECT User_id FROM users WHERE CONCAT(F_name, ' ', L_name) = ? AND Role IN ('Faculty', 'Admin')");
                $stmt->bind_param('s', $facultyName);
                $stmt->execute();
                $stmt->bind_result($facultyId);
                $stmt->fetch();
                $stmt->close();
                
                // If not found, try case-insensitive match
                if (!$facultyId) {
                    $stmt = $conn->prepare("SELECT User_id FROM users WHERE LOWER(CONCAT(F_name, ' ', L_name)) = LOWER(?) AND Role IN ('Faculty', 'Admin')");
                    $stmt->bind_param('s', $facultyName);
                    $stmt->execute();
                    $stmt->bind_result($facultyId);
                    $stmt->fetch();
                    $stmt->close();
                }
                
                if (!$facultyId) {
                    $rowErrors[] = "Faculty '$facultyName' not found in database";
                }
            }
            
            // Format times
            if (!empty($startTime)) {
                if (strpos($startTime, ':') === false) {
                    // Convert Excel time if needed
                    if (is_numeric($startTime)) {
                        try {
                            $startTime = Date::excelToDateTimeObject($startTime)->format('H:i');
                        } catch (Exception $e) {
                            $rowErrors[] = 'Invalid start time format';
                        }
                    }
                } else {
                    // Normalize time format (e.g., 9:00 to 09:00)
                    $timeParts = explode(':', $startTime);
                    if (count($timeParts) == 2) {
                        $startTime = sprintf('%02d:%02d', (int)$timeParts[0], (int)$timeParts[1]);
                    }
                }
            }
            
            if (!empty($endTime)) {
                if (strpos($endTime, ':') === false) {
                    if (is_numeric($endTime)) {
                        try {
                            $endTime = Date::excelToDateTimeObject($endTime)->format('H:i');
                        } catch (Exception $e) {
                            $rowErrors[] = 'Invalid end time format';
                        }
                    }
                } else {
                    // Normalize time format (e.g., 21:00 to 21:00, 9:00 to 09:00)
                    $timeParts = explode(':', $endTime);
                    if (count($timeParts) == 2) {
                        $endTime = sprintf('%02d:%02d', (int)$timeParts[0], (int)$timeParts[1]);
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
            'subject_description' => $subjectDescription,
            'course_section' => $courseSection,
            'day' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room_code' => $roomCode,
            'faculty_name' => $facultyName,
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