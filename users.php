<?php 
session_start();
include 'conn.php';
include "session_auth.php";

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get stats for KPI cards
$student_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Student' AND Status='Active'"))['c'];
$faculty_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Faculty' AND Status='Active'"))['c'];
$admin_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Admin' AND Status='Active'"))['c'];
$cleaning_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Cleaning' AND Status='Active'"))['c'];
$security_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Security' AND Status='Active'"))['c'];
$inactive_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Status='Inactive'"))['c'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/loalogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    <title>USERS - Smart Classroom</title>
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
            font-size: 0.85rem;
        }
        .import-preview-table .valid-row {
            background-color: #d4edda;
        }
        .import-preview-table .invalid-row {
            background-color: #f8d7da;
        }
        .error-list {
            font-size: 0.75rem;
            color: #dc3545;
            margin: 0;
            padding-left: 1rem;
        }
        .stat-icon-box.bg-green-soft { background-color: rgba(40, 167, 69, 0.15); }
        .text-green { color: #28a745; }
        .stat-icon-box.bg-orange-soft { background-color: rgba(253, 126, 20, 0.15); }
        .text-orange { color: #fd7e14; }
        .stat-icon-box.bg-indigo-soft { background-color: rgba(102, 16, 242, 0.15); }
        .text-indigo { color: #6610f2; }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .loading-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>
    
    <div class="main-content p-4">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">User Management</h3>
                <div class="input-group room-search" style="max-width: 400px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Name, RFID, Role...">
                </div>
            </div>
        </div>
        
        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-purple-soft text-purple me-3"><i class="fas fa-user-graduate fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="studentCount"><?php echo $student_count; ?></h2><small class="text-muted">Students</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-pink-soft text-pink me-3"><i class="fas fa-chalkboard-teacher fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="facultyCount"><?php echo $faculty_count; ?></h2><small class="text-muted">Faculty</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-indigo-soft text-indigo me-3"><i class="fas fa-user-shield fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="adminCount"><?php echo $admin_count; ?></h2><small class="text-muted">Admins</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-green-soft text-green me-3"><i class="fas fa-broom fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="cleaningCount"><?php echo $cleaning_count; ?></h2><small class="text-muted">Cleaning</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-orange-soft text-orange me-3"><i class="fas fa-shield-alt fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="securityCount"><?php echo $security_count; ?></h2><small class="text-muted">Security</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-cyan-soft text-cyan me-3"><i class="fas fa-user-slash fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold" id="inactiveCount"><?php echo $inactive_count; ?></h2><small class="text-muted">Inactive</small></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters & Buttons -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="roleFilter">
                    <option value="all" selected>All Roles</option>
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Admin">Admin</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Security">Security</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="courseFilter">
                    <option value="all" selected>All Courses</option>
                    <?php
                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                    $courses_result = $conn->query($courses_sql);
                    while ($course = $courses_result->fetch_assoc()) {
                        echo "<option value='{$course['CourseSection']}'>{$course['CourseSection']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="statusFilter">
                    <option value="all" selected>All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="secondary-btn w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i> Clear Filters
                </button>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="main-btn w-100" id="addUserBtn">
                    <i class="fas fa-user-plus me-1"></i> Add User
                </button>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="main-btn w-100 excel-btn" id="importExcelBtn">
                    <i class="fas fa-file-excel me-1"></i> Import Excel
                </button>
            </div>
        </div>

        <!-- Users Container -->
        <div id="usersContainer">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading users...</p>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="user_id">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="action" value="save_user">
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="rfidTag" class="form-label">RFID Tag *</label>
                                <input type="text" class="form-control" id="rfidTag" name="rfid_tag" required placeholder="Scan or enter RFID tag">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="f_name" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="l_name" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="Student">Student</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Cleaning">Cleaning Personnel</option>
                                    <option value="Security">Security Personnel</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12" id="courseSectionContainer">
                                <label for="courseSection" class="form-label">Course Section (For Students)</label>
                                <select class="form-select" id="courseSection" name="course_id">
                                    <option value="">Select Course Section</option>
                                    <?php
                                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                                    $courses_result = $conn->query($courses_sql);
                                    while ($course = $courses_result->fetch_assoc()) {
                                        echo "<option value='{$course['CourseSection_id']}'>{$course['CourseSection']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="main-btn" id="saveUserBtn">Save User</button>
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
                        Import Users from Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Excel File Requirements:</h6>
                                <p class="small mb-2">Your Excel file must have these <strong>6 columns</strong> in this exact order:</p>
                                <code>RFID Tag | First Name | Last Name | Role | Status | Course Section</code>
                                <p class="small text-muted mb-1">Valid Roles: Student, Faculty, Admin, Cleaning, Security</p>
                                <p class="small text-muted">Valid Status: Active, Inactive (defaults to Active if empty)</p>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-success" id="downloadTemplateBtn">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="importExcelForm" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" id="importCsrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label fw-bold">Select Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="excel_file" accept=".xlsx, .xls" required>
                            <div class="form-text">Supported formats: .xlsx, .xls (Max size: 5MB)</div>
                        </div>
                        
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">Import Options:</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="skipFirstRow" checked>
                                    <label class="form-check-label" for="skipFirstRow">Skip first row (headers)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="updateExisting">
                                    <label class="form-check-label" for="updateExisting">Update existing users (if RFID tag found)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="autoCreateMissing">
                                    <label class="form-check-label" for="autoCreateMissing">Auto-create missing course sections</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div id="importProgress" style="display: none;">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="importProgressBar" style="width: 0%">0%</div>
                        </div>
                        <p class="text-center text-muted small" id="progressStatus">Processing...</p>
                    </div>
                    
                    <div id="importPreview" style="display: none;">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Preview & Validation Results</h6>
                            <span class="badge bg-secondary" id="previewCount">0 rows</span>
                        </div>
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-sm table-bordered import-preview-table">
                                <thead class="table-light">
                                    <tr><th>#</th><th>RFID</th><th>First Name</th><th>Last Name</th><th>Role</th><th>Status</th><th>Course</th><th>Valid</th><th>Errors</th></tr>
                                </thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                        <div id="previewSummary" class="mt-2 small"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="processImportBtn" disabled>
                        <i class="fas fa-upload me-1"></i> Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let allUsers = [];
            
            // Load users on page load
            loadUsers();
            
            function loadUsers() {
                $('#loadingOverlay').show();
                $.ajax({
                    url: 'users_api.php',
                    type: 'POST',
                    data: { action: 'get_users' },
                    dataType: 'json',
                    success: function(response) {
                        $('#loadingOverlay').hide();
                        if (response.success) {
                            allUsers = response.users;
                            renderUsers(allUsers);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingOverlay').hide();
                        console.error('Error:', status, error);
                        Swal.fire('Error', 'Failed to load users: ' + status, 'error');
                    }
                });
            }
            
            function renderUsers(users) {
                const roleFilter = $('#roleFilter').val();
                const courseFilter = $('#courseFilter').val();
                const statusFilter = $('#statusFilter').val();
                const searchTerm = $('#searchInput').val().toLowerCase();
                
                let filteredUsers = users.filter(user => {
                    if (roleFilter !== 'all' && user.Role !== roleFilter) return false;
                    if (courseFilter !== 'all' && user.CourseSection !== courseFilter) return false;
                    if (statusFilter !== 'all' && user.Status !== statusFilter) return false;
                    if (searchTerm && !`${user.F_name} ${user.L_name} ${user.Rfid_tag} ${user.Role}`.toLowerCase().includes(searchTerm)) return false;
                    return true;
                });
                
                if (filteredUsers.length === 0) {
                    $('#usersContainer').html('<div class="text-center py-5"><i class="fas fa-users fa-3x text-muted mb-3"></i><p class="text-muted">No users found</p></div>');
                    return;
                }
                
                const students = filteredUsers.filter(u => u.Role === 'Student');
                const faculty = filteredUsers.filter(u => u.Role === 'Faculty');
                const staff = filteredUsers.filter(u => ['Admin', 'Cleaning', 'Security'].includes(u.Role));
                
                let html = `
                    <ul class="nav nav-pills mb-4" id="userTabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#studentsTab">Students <span class="badge bg-danger ms-1">${students.length}</span></button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#facultyTab">Faculty <span class="badge bg-danger ms-1">${faculty.length}</span></button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#staffTab">Staff <span class="badge bg-danger ms-1">${staff.length}</span></button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#allTab">All Users <span class="badge bg-danger ms-1">${filteredUsers.length}</span></button></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="studentsTab">${renderUserTable(students, 'students')}</div>
                        <div class="tab-pane fade" id="facultyTab">${renderUserTable(faculty, 'faculty')}</div>
                        <div class="tab-pane fade" id="staffTab">${renderUserTable(staff, 'staff')}</div>
                        <div class="tab-pane fade" id="allTab">${renderUserTable(filteredUsers, 'all')}</div>
                    </div>
                `;
                
                $('#usersContainer').html(html);
            }
            
            function renderUserTable(users, type) {
                if (users.length === 0) return '<div class="text-center py-4"><p class="text-muted">No users found</p></div>';
                
                let table = '<div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>ID</th><th>RFID</th><th>First Name</th><th>Last Name</th><th>Role</th>';
                if (type === 'students' || type === 'all') table += '<th>Course</th>';
                table += '<th>Status</th><th class="text-center">Actions</th></thead><tbody>';
                
                users.forEach(user => {
                    table += `<tr>
                        <td>${user.User_id}</td>
                        <td>${escapeHtml(user.Rfid_tag)}</td>
                        <td>${escapeHtml(user.F_name)}</td>
                        <td>${escapeHtml(user.L_name)}</td>
                        <td><span class="badge bg-secondary">${user.Role}</span></td>`;
                    if (type === 'students' || type === 'all') {
                        table += `<td>${escapeHtml(user.CourseSection || 'N/A')}</td>`;
                    }
                    table += `<td><span class="badge ${user.Status === 'Active' ? 'bg-success' : 'bg-secondary'}">${user.Status}</span></td>
                        <td class="text-center">
                            <button class="btn btn-success btn-sm me-1" onclick='editUser(${JSON.stringify(user).replace(/'/g, "&#39;")})'><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.User_id}, '${escapeHtml(user.F_name)}', '${escapeHtml(user.L_name)}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
                
                table += '</tbody></table></div>';
                return table;
            }
            
            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }
            
            // Filter handlers
            $('#roleFilter, #courseFilter, #statusFilter, #searchInput').on('change keyup', function() {
                renderUsers(allUsers);
            });
            
            $('#clearFilters').click(function() {
                $('#roleFilter').val('all');
                $('#courseFilter').val('all');
                $('#statusFilter').val('all');
                $('#searchInput').val('');
                renderUsers(allUsers);
            });
            
            // Add User
            $('#addUserBtn').click(function() {
                $('#userModalLabel').text('Add New User');
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#courseSectionContainer').show();
                new bootstrap.Modal(document.getElementById('userModal')).show();
            });
            
            // Save User
            $('#saveUserBtn').click(function() {
                const formData = new FormData($('#userForm')[0]);
                
                $('#loadingOverlay').show();
                $.ajax({
                    url: 'users_api.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loadingOverlay').hide();
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        $('#loadingOverlay').hide();
                        Swal.fire('Error', 'Failed to save user', 'error');
                    }
                });
            });
            
            $('#role').change(function() {
                if ($(this).val() === 'Student') {
                    $('#courseSectionContainer').show();
                } else {
                    $('#courseSectionContainer').hide();
                }
            });
            
            // Import Excel Functions
            let importedData = [];
            
            $('#importExcelBtn').click(function() {
                $('#importExcelForm')[0].reset();
                $('#importPreview').hide();
                $('#importProgress').hide();
                $('#processImportBtn').prop('disabled', true);
                $('#skipFirstRow').prop('checked', true);
                $('#updateExisting').prop('checked', false);
                $('#autoCreateMissing').prop('checked', false);
                importedData = [];
                new bootstrap.Modal(document.getElementById('importExcelModal')).show();
            });
            
            $('#downloadTemplateBtn').click(function() {
                const wsData = [
                    ['RFID Tag', 'First Name', 'Last Name', 'Role', 'Status', 'Course Section'],
                    ['RFID001', 'John', 'Doe', 'Student', 'Active', 'BSIT-1A'],
                    ['RFID002', 'Jane', 'Smith', 'Faculty', 'Active', ''],
                    ['RFID003', 'Mike', 'Johnson', 'Cleaning', 'Active', ''],
                    ['RFID004', 'Sarah', 'Williams', 'Security', 'Active', ''],
                    ['RFID005', 'Admin', 'User', 'Admin', 'Active', '']
                ];
                const ws = XLSX.utils.aoa_to_sheet(wsData);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Users_Template');
                XLSX.writeFile(wb, 'user_import_template.xlsx');
            });
            
            $('#excelFile').change(function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                        const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                        processExcelData(jsonData);
                    } catch (error) {
                        Swal.fire('Error', 'Failed to read Excel file: ' + error.message, 'error');
                    }
                };
                reader.readAsArrayBuffer(file);
            });
            
            function processExcelData(data) {
                let startRow = $('#skipFirstRow').is(':checked') ? 1 : 0;
                importedData = [];
                const previewData = [];
                const validRoles = ['Student', 'Faculty', 'Admin', 'Cleaning', 'Security'];
                const validStatus = ['Active', 'Inactive'];
                
                for (let i = startRow; i < data.length; i++) {
                    const row = data[i];
                    if (!row || row.length < 4) continue;
                    
                    const rfid = String(row[0] || '').trim();
                    const fname = String(row[1] || '').trim();
                    const lname = String(row[2] || '').trim();
                    const role = String(row[3] || '').trim();
                    const status = String(row[4] || 'Active').trim();
                    const courseSection = String(row[5] || '').trim();
                    
                    const errors = [];
                    if (!rfid) errors.push('RFID required');
                    if (!fname) errors.push('First name required');
                    if (!lname) errors.push('Last name required');
                    if (!role) errors.push('Role required');
                    if (!validRoles.includes(role)) errors.push(`Invalid role: ${role}`);
                    if (status && !validStatus.includes(status)) errors.push(`Invalid status: ${status}`);
                    if (role === 'Student' && !courseSection) errors.push('Course section required for students');
                    
                    const isValid = errors.length === 0;
                    
                    importedData.push({
                        rfid_tag: rfid, f_name: fname, l_name: lname,
                        role: role, status: status, course_section: courseSection,
                        isValid: isValid, errors: errors
                    });
                    
                    previewData.push({
                        row_num: i + 1, rfid: rfid || '<empty>', fname: fname || '<empty>',
                        lname: lname || '<empty>', role: role || '<empty>', status: status,
                        course: courseSection || 'N/A', isValid: isValid, errors: errors
                    });
                }
                
                displayPreview(previewData);
                const validCount = previewData.filter(r => r.isValid).length;
                $('#processImportBtn').prop('disabled', validCount === 0);
            }
            
            function displayPreview(previewData) {
                $('#previewCount').text(`${previewData.length} rows`);
                let html = '';
                previewData.forEach(row => {
                    html += `<tr class="${row.isValid ? 'valid-row' : 'invalid-row'}">
                        <td>${row.row_num}</td>
                        <td>${escapeHtml(row.rfid)}</td>
                        <td>${escapeHtml(row.fname)}</td>
                        <td>${escapeHtml(row.lname)}</td>
                        <td>${escapeHtml(row.role)}</td>
                        <td>${escapeHtml(row.status)}</td>
                        <td>${escapeHtml(row.course)}</td>
                        <td>${row.isValid ? '<span class="badge bg-success">✓ Valid</span>' : '<span class="badge bg-danger">✗ Invalid</span>'}</td>
                        <td>${row.errors.length > 0 ? `<ul class="error-list">${row.errors.map(e => `<li>${escapeHtml(e)}</li>`).join('')}</ul>` : '<span class="text-success">No errors</span>'}</td>
                    </tr>`;
                });
                $('#previewBody').html(html);
                
                const validCount = previewData.filter(r => r.isValid).length;
                const invalidCount = previewData.length - validCount;
                $('#previewSummary').html(`
                    <strong>Summary:</strong><br>
                    ✅ Valid: ${validCount}<br>
                    ❌ Invalid: ${invalidCount}<br>
                    ${invalidCount > 0 ? '<span class="text-danger">⚠️ Please fix errors before importing</span>' : '<span class="text-success">✓ All rows are valid! Ready to import.</span>'}
                `);
                $('#importPreview').show();
            }
            
            $('#processImportBtn').click(function() {
                const validData = importedData.filter(item => item.isValid);
                if (validData.length === 0) {
                    Swal.fire('Error', 'No valid data to import', 'error');
                    return;
                }
                
                Swal.fire({
                    title: 'Confirm Import',
                    text: `Are you sure you want to import ${validData.length} users?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, import now'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#importProgress').show();
                        $('#importProgressBar').css('width', '30%').text('Preparing...');
                        $('#progressStatus').text('Preparing data for import...');
                        
                        const formData = new FormData();
                        formData.append('action', 'process_import');
                        formData.append('csrf_token', $('#importCsrfToken').val());
                        formData.append('import_data', JSON.stringify(validData));
                        formData.append('update_existing', $('#updateExisting').is(':checked') ? '1' : '0');
                        formData.append('auto_create_missing', $('#autoCreateMissing').is(':checked') ? '1' : '0');
                        
                        $('#importProgressBar').css('width', '60%').text('Importing...');
                        $('#progressStatus').text('Importing to database, please wait...');
                        
                        $.ajax({
                            url: 'users_api.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            timeout: 120000,
                            success: function(response) {
                                $('#importProgress').hide();
                                if (response.success) {
                                    Swal.fire('Success', response.message, 'success').then(() => {
                                        bootstrap.Modal.getInstance(document.getElementById('importExcelModal')).hide();
                                        location.reload();
                                    });
                                } else {
                                    let errorMsg = response.message;
                                    if (response.error_details && response.error_details.length > 0) {
                                        errorMsg += '\n\nErrors:\n' + response.error_details.slice(0, 10).join('\n');
                                    }
                                    Swal.fire('Warning', errorMsg, 'warning');
                                }
                            },
                            error: function(xhr, status, error) {
                                $('#importProgress').hide();
                                console.error('AJAX Error:', status, error);
                                Swal.fire('Error', 'Import failed: ' + error, 'error');
                            }
                        });
                    }
                });
            });
        });
        
        function editUser(user) {
            $('#userModalLabel').text('Edit User');
            $('#userId').val(user.User_id);
            $('#rfidTag').val(user.Rfid_tag);
            $('#firstName').val(user.F_name);
            $('#lastName').val(user.L_name);
            $('#role').val(user.Role);
            $('#status').val(user.Status);
            $('#courseSection').val(user.cs_id || '');
            $('#courseSectionContainer').toggle(user.Role === 'Student');
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }
        
        function deleteUser(id, fname, lname) {
            Swal.fire({
                title: `Delete ${fname} ${lname}?`,
                text: "User must be Inactive before deletion.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loadingOverlay').show();
                    $.ajax({
                        url: 'users_api.php',
                        type: 'POST',
                        data: { action: 'delete_user', user_id: id, csrf_token: '<?php echo $_SESSION['csrf_token']; ?>' },
                        success: function(response) {
                            $('#loadingOverlay').hide();
                            if (response.success) {
                                location.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            $('#loadingOverlay').hide();
                            Swal.fire('Error', 'Delete failed', 'error');
                        }
                    });
                }
            });
        }
    </script>
    
</body>
</html>