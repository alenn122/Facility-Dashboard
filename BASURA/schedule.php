<?php 
include 'conn.php';
include "session_auth.php";

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="img/loalogo.png" type="image/x-icon">
    <!-- ICON CDN FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- SWEETALERT2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SELECT2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>SCHEDULES</title>
    <style>
        .excel-btn {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .excel-btn:hover {
            background-color: #218838 !important;
            border-color: #1e7e34 !important;
        }
        .import-preview-table {
            font-size: 0.9rem;
        }
        .import-preview-table .valid-row {
            background-color: #d4edda;
        }
        .import-preview-table .invalid-row {
            background-color: #f8d7da;
        }
        .error-list {
            font-size: 0.8rem;
            color: #dc3545;
            margin: 0;
            padding-left: 1rem;
        }
    </style>
</head>

<body>

        <!-- NAVIGATION SIDEBAR -->
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content p-4">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Schedule Management</h3>

                <div class="input-group room-search" style="max-width: 400px;">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" 
                           placeholder="Search by Course, Faculty, Room...">
                </div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="courseFilter">
                    <option value="all" selected>All Course Sections</option>
                    <?php
                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                    $courses_result = $conn->query($courses_sql);
                    while ($course = $courses_result->fetch_assoc()) {
                        echo "<option value='{$course['CourseSection_id']}'>{$course['CourseSection']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="dayFilter">
                    <option value="all" selected>All Days</option>
                    <option value="Mon">Monday</option>
                    <option value="Tue">Tuesday</option>
                    <option value="Wed">Wednesday</option>
                    <option value="Thu">Thursday</option>
                    <option value="Fri">Friday</option>
                    <option value="Sat">Saturday</option>
                    <option value="Sun">Sunday</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="facultyFilter">
                    <option value="all" selected>All Faculty</option>
                    <?php
                    $faculty_sql = "SELECT * FROM users WHERE Role IN ('Faculty', 'Admin') ORDER BY F_name, L_name";
                    $faculty_result = $conn->query($faculty_sql);
                    while ($faculty = $faculty_result->fetch_assoc()) {
                        $fullname = $faculty['F_name'] . ' ' . $faculty['L_name'];
                        echo "<option value='{$faculty['User_id']}'>{$fullname}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="roomFilter">
                    <option value="all" selected>All Rooms</option>
                    <?php
                    $rooms_sql = "SELECT * FROM classrooms ORDER BY Room_code";
                    $rooms_result = $conn->query($rooms_sql);
                    while ($room = $rooms_result->fetch_assoc()) {
                        echo "<option value='{$room['Room_id']}'>{$room['Room_code']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="secondary-btn w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i> Clear Filters
                </button>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="main-btn w-100" id="addScheduleBtn">
                    <i class="fas fa-plus me-1"></i> Add Schedule
                </button>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="main-btn w-100 excel-btn" id="importExcelBtn">
                    <i class="fas fa-file-excel me-1"></i> Import Excel
                </button>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading schedules...</p>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-4" style="display: none;">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">No schedules found.</p>
        </div>

        <!-- Schedules Container -->
        <div id="schedulesContainer"></div>

    </div>

    <!-- Add/Edit Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Add New Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <input type="hidden" id="scheduleId" name="schedule_id">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subject *</label>
                                <select class="form-select select2-enable" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <?php
                                    $subjects_sql = "SELECT * FROM subject ORDER BY Code";
                                    $subjects_result = $conn->query($subjects_sql);
                                    while ($subject = $subjects_result->fetch_assoc()) {
                                        echo "<option value='{$subject['Subject_id']}'>{$subject['Code']} - {$subject['Description']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="room" class="form-label">Room *</label>
                                <select class="form-select" id="room" name="room" required>
                                    <option value="">Select Room</option>
                                    <?php
                                    $rooms_sql = "SELECT * FROM classrooms ORDER BY Room_code";
                                    $rooms_result = $conn->query($rooms_sql);
                                    while ($room = $rooms_result->fetch_assoc()) {
                                        echo "<option value='{$room['Room_id']}'>{$room['Room_code']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="faculty" class="form-label">Faculty *</label>
                                <select class="form-select" id="faculty" name="faculty" required>
                                    <option value="">Select Faculty</option>
                                    <?php
                                    $faculty_sql = "SELECT * FROM users WHERE Role IN ('Faculty', 'Admin') ORDER BY F_name, L_name";
                                    $faculty_result = $conn->query($faculty_sql);
                                    while ($faculty = $faculty_result->fetch_assoc()) {
                                        $fullname = $faculty['F_name'] . ' ' . $faculty['L_name'];
                                        echo "<option value='{$faculty['User_id']}'>{$fullname}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="day" class="form-label">Day *</label>
                                <select class="form-select" id="day" name="day" required>
                                    <option value="">Select Day</option>
                                    <option value="Mon">Monday</option>
                                    <option value="Tue">Tuesday</option>
                                    <option value="Wed">Wednesday</option>
                                    <option value="Thu">Thursday</option>
                                    <option value="Fri">Friday</option>
                                    <option value="Sat">Saturday</option>
                                    <option value="Sun">Sunday</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="startTime" class="form-label">Start Time *</label>
                                <div class="time-input-group">
                                    <input type="time" class="form-control" id="startTime" name="start_time" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="endTime" class="form-label">End Time *</label>
                                <div class="time-input-group">
                                    <input type="time" class="form-control" id="endTime" name="end_time" required>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="courseSections" class="form-label">Course Sections *</label>
                                <select class="form-select select2-enable" id="courseSections" name="course_sections[]" multiple required style="height: 150px;">
                                    <?php
                                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                                    $courses_result = $conn->query($courses_sql);
                                    while ($course = $courses_result->fetch_assoc()) {
                                        echo "<option value='{$course['CourseSection_id']}'>{$course['CourseSection']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="multi-select-help">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Hold Ctrl (Windows) or Cmd (Mac) to select multiple sections
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="main-btn" id="saveScheduleBtn">Save Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Excel Modal -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">
                        <i class="fas fa-file-excel me-2" style="color: #28a745;"></i>
                        Import Schedules from Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Template Download -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Excel File Requirements:</h6>
                                <p class="small mb-2">Your Excel file must have these <strong>8 columns</strong> in this exact order:</p>
                                <code>Code | Description | Course Section | Day | Start Time | End Time | Room Code | Faculty Name</code>
                                <p class="small text-danger mb-0 mt-1"><i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> Same format as provided</p>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-success" id="downloadTemplateBtn">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upload Form -->
                    <form id="importExcelForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label fw-bold">Select Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="excel_file" 
                                   accept=".xlsx, .xls, .csv" required>
                            <div class="form-text">Supported formats: .xlsx, .xls, .csv (Max size: 5MB)</div>
                        </div>
                        
                        <!-- Import Options -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">Import Options:</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="skipFirstRow" checked>
                                    <label class="form-check-label" for="skipFirstRow">
                                        Skip first row (headers)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="updateExisting">
                                    <label class="form-check-label" for="updateExisting">
                                        Update existing schedules (if found)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="autoCreateMissing">
                                    <label class="form-check-label" for="autoCreateMissing">
                                        Auto-create missing subjects, rooms, and course sections
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="validateOnly">
                                    <label class="form-check-label" for="validateOnly">
                                        Validate only (don't import)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Progress Bar -->
                    <div id="importProgress" style="display: none;">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                 id="importProgressBar" role="progressbar" style="width: 0%">0%</div>
                        </div>
                    </div>
                    
                    <!-- Preview Area -->
                    <div id="importPreview" style="display: none;">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Preview</h6>
                            <span class="badge bg-secondary" id="previewCount">0 rows</span>
                        </div>
                        <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-sm table-bordered import-preview-table">
                                <thead class="table-light">
                                    <tr id="previewHeader"></tr>
                                </thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                        <div id="previewSummary" class="mt-2 small"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="validateImportBtn" disabled>
                        <i class="fas fa-check-circle me-1"></i> Validate
                    </button>
                    <button type="button" class="btn btn-success" id="processImportBtn" disabled>
                        <i class="fas fa-upload me-1"></i> Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <!-- JAVASCRIPT -->
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/schedule.js?v=2.0"></script>
    <script>
        $(document).ready(function() {
            // This targets the Subject and Course Sections IDs
            $('#subject, #courseSections').select2({
                tags: true,
                placeholder: "Select or type to add...",
                width: '100%',
                dropdownParent: $('#scheduleModal'),
                containerCssClass: "custom-select2-container"
            });
        });
    </script>
    
</body>
</html>