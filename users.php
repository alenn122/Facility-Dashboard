<?php
// 1. Session and Auth must come first before ANY output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conn.php';
include "session_auth.php";

// 2. CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 3. Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// --- BACKEND LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => 'CSRF token validation failed']));
    }

    if (isset($_POST['save_user'])) {
        $id = !empty($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $rfid = $_POST['rfid_tag'];
        $fname = $_POST['f_name'];
        $lname = $_POST['l_name'];
        $role = $_POST['role'];
        $status = $_POST['status'];
        $isIrregular = isset($_POST['is_irregular']) ? 1 : 0;
        $courseId = !empty($_POST['course_id']) ? intval($_POST['course_id']) : null;
        
        // Input validation
        $errors = [];
        if (empty($rfid)) $errors[] = "RFID tag is required";
        if (empty($fname)) $errors[] = "First name is required";
        if (empty($lname)) $errors[] = "Last name is required";
        if (!in_array($role, ['Student', 'Faculty', 'Admin'])) $errors[] = "Invalid role";
        
        if (!empty($errors)) {
            $error_msg = implode(", ", $errors);
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['status' => 'error', 'message' => $error_msg]);
                exit;
            }
            $_SESSION['error_message'] = $error_msg;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
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
        
        if ($res) {
            $current_user_id = ($id > 0) ? $id : mysqli_insert_id($conn);
            
            $deleteStmt = mysqli_prepare($conn, "DELETE FROM individual_permissions WHERE User_id = ?");
            mysqli_stmt_bind_param($deleteStmt, "i", $current_user_id);
            mysqli_stmt_execute($deleteStmt);
            
            if ($isIrregular && isset($_POST['special_schedule_ids']) && is_array($_POST['special_schedule_ids'])) {
                $insertStmt = mysqli_prepare($conn, "INSERT INTO individual_permissions (User_id, Schedule_id, Reason) VALUES (?, ?, ?)");
                $reason = 'Irregular/Working Student';
                foreach ($_POST['special_schedule_ids'] as $sched_id) {
                    $sched_id = intval($sched_id);
                    mysqli_stmt_bind_param($insertStmt, "iis", $current_user_id, $sched_id, $reason);
                    mysqli_stmt_execute($insertStmt);
                }
                mysqli_stmt_close($insertStmt);
            }
            mysqli_stmt_close($deleteStmt);
        }
        mysqli_stmt_close($stmt);
        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => $res ? 'success' : 'error', 'message' => $res ? 'User saved!' : mysqli_error($conn)]);
            exit;
        }
        
        $_SESSION[$res ? 'success_message' : 'error_message'] = $res ? ($id > 0 ? 'User updated!' : 'User added!') : 'Database error: ' . mysqli_error($conn);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // SECURE DELETE LOGIC
    if (isset($_POST['delete_user'])) {
        $id = intval($_POST['user_id']);

        // 1. Fetch user status and role
        $user_check = mysqli_query($conn, "SELECT Status, Role FROM users WHERE User_id=$id");
        $user_data = mysqli_fetch_assoc($user_check);

        if ($user_data) {
            // RULE: Must be Inactive to delete
            if ($user_data['Status'] === 'Active') {
                $_SESSION['error_message'] = 'Cannot delete an Active user. Set status to Inactive first.';
            } else {
                // 2. Check if this user is assigned to any schedules (Prevents Foreign Key Error)
                $sched_check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM schedule WHERE Faculty_id=$id");
                $has_schedules = mysqli_fetch_assoc($sched_check)['total'];

                if ($has_schedules > 0) {
                    $_SESSION['error_message'] = 'Cannot delete: This user is still assigned to ' . $has_schedules . ' class schedule(s).';
                } else {
                    // 3. Safe to delete
                    $res = mysqli_query($conn, "DELETE FROM users WHERE User_id=$id");
                    if ($res) {
                        $_SESSION['success_message'] = 'User deleted successfully!';
                    } else {
                        $_SESSION['error_message'] = 'Delete failed: ' . mysqli_error($conn);
                    }
                }
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Stats Queries
$student_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role='Student' AND Status='Active'"))['c'];
$faculty_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Role IN ('Faculty','Admin') AND Status='Active'"))['c'];
$inactive_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE Status='Inactive'"))['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/loalogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>USERS - Smart Classroom</title>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content p-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">User Management</h3>
                <div class="input-group room-search">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Name, ID, Course...">
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-purple-soft text-purple me-3"><i class="fas fa-user-graduate fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold"><?php echo $student_count; ?></h2><small class="text-muted">Active Students</small></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-pink-soft text-pink me-3"><i class="fas fa-chalkboard-teacher fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold"><?php echo $faculty_count; ?></h2><small class="text-muted">Active Faculty</small></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-box bg-cyan-soft text-cyan me-3"><i class="fas fa-user-slash fs-4"></i></div>
                        <div><h2 class="mb-0 fw-bold"><?php echo $inactive_count; ?></h2><small class="text-muted">Inactive Users</small></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <ul class="nav nav-pills mb-4" id="userTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active custom-pill" data-bs-toggle="pill" data-bs-target="#studentsTab" type="button">Students <span class="badge ms-1 bg-danger"><?php echo $student_count; ?></span></button></li>
                <li class="nav-item"><button class="nav-link custom-pill" data-bs-toggle="pill" data-bs-target="#facultyTab" type="button">Faculty <span class="badge ms-1 bg-danger"><?php echo $faculty_count; ?></span></button></li>
                <li class="nav-item"><button class="nav-link custom-pill" data-bs-toggle="pill" data-bs-target="#allTab" type="button">All Users</button></li>
            </ul>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <select class="form-select w-auto filter-trigger" id="courseFilter">
                    <option value="">All Courses</option>
                    <?php 
                    $cRes = mysqli_query($conn, "SELECT CourseSection FROM course_section ORDER BY CourseSection");
                    while($c = mysqli_fetch_assoc($cRes)) {
                        echo "<option value='" . htmlspecialchars($c['CourseSection']) . "'>" . htmlspecialchars($c['CourseSection']) . "</option>";
                    }
                    ?>
                </select>
                <select class="form-select w-auto filter-trigger" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button class="btn btn-secondary ms-md-auto" onclick="resetFilters()"><i class="fas fa-times me-2"></i>Clear Filters</button>
                <button class="main-btn" onclick="openAdd()"><i class="fas fa-user-plus me-2"></i>Add User</button>
            </div>

            <div class="tab-content">
                <?php
                $tabs = ['studentsTab' => "WHERE u.Role = 'Student'", 'facultyTab' => "WHERE u.Role IN ('Faculty','Admin')", 'allTab' => ""];
                $first = true;
                foreach ($tabs as $tabId => $cond):
                    $sql = "SELECT u.*, cs.CourseSection,u.courseSection_id as cs_id, GROUP_CONCAT(ip.Schedule_id) as all_special_ids 
                            FROM users u 
                            LEFT JOIN course_section cs ON u.courseSection_id = cs.CourseSection_id 
                            LEFT JOIN individual_permissions ip ON u.User_id = ip.User_id
                            $cond GROUP BY u.User_id ORDER BY u.User_id DESC";
                    $result = mysqli_query($conn, $sql);
                ?>
                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="<?php echo $tabId; ?>">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th><th>RFID</th><th>First</th><th>Last</th>
                                    <?php if($tabId !== 'facultyTab'): ?><th>Course</th><?php endif; ?>
                                    <th>Status</th><th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): 
                                    $isIrreg = !empty($row['all_special_ids']); 
                                ?>
                                <tr class="user-row <?php echo $isIrreg ? 'irregular-indicator':''; ?>" data-course="<?php echo htmlspecialchars($row['CourseSection'] ?? ''); ?>" data-status="<?php echo htmlspecialchars($row['Status']); ?>">
                                    <td><?php echo htmlspecialchars($row['User_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Rfid_tag']); ?></td>
                                    <td><?php echo htmlspecialchars($row['F_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['L_name']); ?></td>
                                    <?php if($tabId !== 'facultyTab'): ?>
                                    <td>
                                        <?php echo htmlspecialchars($row['CourseSection'] ?? 'N/A'); ?>
                                        <?php if($isIrreg): ?><span class="badge bg-warning text-dark ms-1">Irregular/Working</span><?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td><span class="badge <?php echo $row['Status']=='Active'?'bg-success':'bg-secondary'; ?>"><?php echo htmlspecialchars($row['Status']); ?></span></td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm me-1" onclick='openEdit(<?php echo json_encode($row); ?>)'><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $row['User_id']; ?>, '<?php echo addslashes($row['F_name']); ?>', '<?php echo addslashes($row['L_name']); ?>')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php $first=false; endforeach; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="m_id">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label small">RFID Tag</label>
                            <span id="scannerStatus" class="badge bg-secondary mb-1" style="font-size: 0.7rem;"><i class="fas fa-plug me-1"></i>Scanner Offline</span>
                        </div>
                        <input type="text" name="rfid_tag" id="m_rfid" class="form-control" required placeholder="Scan RFID card...">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label small">First Name</label><input type="text" name="f_name" id="m_fname" class="form-control" required></div>
                        <div class="col-6"><label class="form-label small">Last Name</label><input type="text" name="l_name" id="m_lname" class="form-control" required></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Role</label>
                        <select name="role" id="m_role" class="form-select" onchange="toggleRole()">
                            <option value="Student">Student</option><option value="Faculty">Faculty</option><option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3" id="m_course_container">
                        <label class="form-label small">Course Section</label>
                        <select name="course_id" id="m_course" class="form-select">
                            <option value="">None</option>
                            <?php 
                            $cRes = mysqli_query($conn, "SELECT * FROM course_section ORDER BY CourseSection");
                            while($c = mysqli_fetch_assoc($cRes)) {
                                echo "<option value='" . $c['CourseSection_id'] . "'>" . htmlspecialchars($c['CourseSection']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3" id="irregular_checkbox_container">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_irregular_checkbox" name="is_irregular">
                            <label class="form-check-label checkbox-label" for="is_irregular_checkbox">Irregular / Working Student</label>
                        </div>
                    </div>
                    <div id="special_schedule_container" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-warning">Special Schedule Overrides</label>
                            <select class="form-select select2-enable" id="m_special_sched" name="special_schedule_ids[]" multiple style="width: 100%;">
                                <?php
                                $sRes = mysqli_query($conn, "SELECT s.Schedule_id, s.Day, sub.Code FROM schedule s JOIN subject sub ON s.Subject_id = sub.Subject_id ORDER BY sub.Code");
                                while($s = mysqli_fetch_assoc($sRes)) {
                                    echo "<option value='" . $s['Schedule_id'] . "'>" . htmlspecialchars($s['Code'] . " (" . $s['Day'] . ")") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label small">Status</label><select name="status" id="m_status" class="form-select"><option value="Active">Active</option><option value="Inactive">Inactive</option></select></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveUserBtn" class="main-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script>
        // JS logic remains the same
        function openAdd() {
            document.getElementById('modalTitle').innerText = "Add New User";
            document.getElementById('m_id').value = "";
            document.querySelector('#addEditModal form').reset();
            $('#m_special_sched').val(null).trigger('change');
            toggleRole();
            new bootstrap.Modal(document.getElementById('addEditModal')).show();
        }

        function openEdit(data) {
            document.getElementById('modalTitle').innerText = "Edit User";
            document.getElementById('m_id').value = data.User_id;
            document.getElementById('m_rfid').value = data.Rfid_tag || '';
            document.getElementById('m_fname').value = data.F_name || '';
            document.getElementById('m_lname').value = data.L_name || '';
            document.getElementById('m_role').value = data.Role || 'Student';
            document.getElementById('m_status').value = data.Status || 'Active';
            document.getElementById('m_course').value = data.cs_id || "";
            
            const isIrreg = data.all_special_ids ? true : false;
            document.getElementById('is_irregular_checkbox').checked = isIrreg;
            $('#m_special_sched').val(isIrreg ? data.all_special_ids.split(',') : null).trigger('change');
            
            toggleRole();
            new bootstrap.Modal(document.getElementById('addEditModal')).show();
        }

        function toggleRole() {
            const role = document.getElementById('m_role').value;
            const isChecked = document.getElementById('is_irregular_checkbox').checked;
            document.getElementById('m_course_container').style.display = (role === 'Student') ? 'block' : 'none';
            document.getElementById('irregular_checkbox_container').style.display = (role === 'Student') ? 'block' : 'none';
            document.getElementById('special_schedule_container').style.display = (role === 'Student' && isChecked) ? 'block' : 'none';
        }

        function deleteUser(id, fname, lname) {
            Swal.fire({
                title: `Delete ${fname} ${lname}?`,
                text: "User must be Inactive.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    const f = document.createElement('form');
                    f.method = 'POST';
                    f.innerHTML = `<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                   <input type="hidden" name="user_id" value="${id}">
                                   <input type="hidden" name="delete_user" value="1">`;
                    document.body.appendChild(f);
                    f.submit();
                }
            });
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const course = document.getElementById('courseFilter').value;
            const status = document.getElementById('statusFilter').value;
            document.querySelectorAll('.user-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowCourse = row.getAttribute('data-course');
                const rowStatus = row.getAttribute('data-status');
                row.style.display = (text.includes(search) && (course === "" || rowCourse === course) && (status === "" || rowStatus === status)) ? "" : "none";
            });
        }

        $(document).ready(function() {
            $('#m_special_sched').select2({ theme: 'bootstrap-5', dropdownParent: $('#addEditModal'), width: '100%' });
            $('#is_irregular_checkbox').on('change', toggleRole);
            $('.filter-trigger, #searchInput').on('input change', applyFilters);

            $('#saveUserBtn').on('click', function() {
                const form = document.querySelector('#addEditModal form');
                if(!form.checkValidity()) return form.reportValidity();
                
                const formData = new FormData(form);
                formData.append('save_user', '1');
                const saveBtn = $(this);
                saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                
                $.ajax({
                    url: window.location.href, 
                    type: 'POST', 
                    data: formData, 
                    processData: false, 
                    contentType: false,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        const data = JSON.parse(res);
                        if(data.status === 'success') {
                            Swal.fire('Success', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                            saveBtn.prop('disabled', false).html('Save Changes');
                        }
                    }
                });
            });
        });
    </script>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo $_SESSION['success_message']; ?>', timer: 2500, showConfirmButton: false });</script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
    <script>Swal.fire({ icon: 'warning', title: 'Warning', text: <?= json_encode($_SESSION['error_message']) ?>, showConfirmButton: true });</script>
    <?php unset($_SESSION['error_message']); endif; ?>
    <script>
        setInterval(function() {
            const modal = document.getElementById('addEditModal');
            const rfidField = document.getElementById('m_rfid');
            const statusBadge = document.getElementById('scannerStatus');

            if (modal && modal.classList.contains('show')) {
                // Use a special flag to check connection without consuming a scan
                fetch('rfid.php?get_last_scan=1')
                .then(response => {
                    if (!response.ok) throw new Error();
                    
                    // Connection is OK - Update Status
                    statusBadge.classList.replace('bg-secondary', 'bg-success');
                    statusBadge.classList.replace('bg-danger', 'bg-success');
                    statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Scanner Online';

                    return response.text();
                })
                .then(data => {
                    const cleanData = data.trim();
                    
                    if (cleanData !== "" && !cleanData.includes("<html") && cleanData !== rfidField.value) {
                        rfidField.value = cleanData;
                        
                        // Visual feedback for successful scan
                        rfidField.style.backgroundColor = "#d4edda";
                        rfidField.style.transition = "background-color 0.5s";
                        setTimeout(() => { rfidField.style.backgroundColor = ""; }, 500);
                    }
                })
                .catch(err => {
                    // Connection Failed - Update Status
                    statusBadge.classList.replace('bg-success', 'bg-danger');
                    statusBadge.classList.replace('bg-secondary', 'bg-danger');
                    statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Scanner Offline';
                });
            }
        }, 1000);

        // Add this inside your script tag
        document.getElementById('m_rfid').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // This stops the page from refreshing
                console.log("Enter key blocked to prevent refresh");
                return false;
            }
        });
        document.getElementById('saveUserBtn').addEventListener('click', function() {
            const form = document.querySelector('#addEditModal form');
            
            // Check if form is valid (required fields)
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            formData.append('save_user', '1');

            // Show loading state on button
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('addEditModal')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload to refresh the table with new data
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    </script>
</body>
</html>