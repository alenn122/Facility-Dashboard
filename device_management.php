<?php
// Include your database connection file
include 'conn.php';

// Update device status to Offline if last seen more than 5 seconds ago
$conn->query("UPDATE devices SET status = 'Offline' WHERE last_seen < (NOW() - INTERVAL 5 SECOND)");

// Handle "Add Device" Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_device'])) {
    $mac_address = trim($_POST['mac_address']);
    $room_id = $_POST['room_id'];
    $device_type = $_POST['device_type'];

    $stmt = $conn->prepare("INSERT INTO devices (mac_address, room_id, device_type, status) VALUES (?, ?, ?, 'Offline')");
    $stmt->bind_param("sis", $mac_address, $room_id, $device_type);
    if ($stmt->execute()) {
        header("Location: device_management.php?add_success=1");
        exit();
    } else {
        header("Location: device_management.php?add_error=1");
        exit();
    }
}

// Handle "Edit Device" Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_device'])) {
    $device_id = $_POST['device_id'];
    $mac_address = trim($_POST['mac_address']);
    $room_id = $_POST['room_id'];
    $device_type = $_POST['device_type'];

    // Debug: Check what data is being received
    error_log("Editing device: ID=$device_id, MAC=$mac_address, Room=$room_id, Type=$device_type");

    $stmt = $conn->prepare("UPDATE devices SET mac_address = ?, room_id = ?, device_type = ? WHERE device_id = ?");
    if ($stmt) {
        $stmt->bind_param("sisi", $mac_address, $room_id, $device_type, $device_id);
        if ($stmt->execute()) {
            header("Location: device_management.php?edit_success=1");
            exit();
        } else {
            error_log("Update failed: " . $stmt->error);
            header("Location: device_management.php?edit_error=1&msg=" . urlencode($stmt->error));
            exit();
        }
    } else {
        error_log("Prepare failed: " . $conn->error);
        header("Location: device_management.php?edit_error=1&msg=" . urlencode($conn->error));
        exit();
    }
}

// Handle "Delete Device" Request
if (isset($_GET['delete_id'])) {
    $device_id = $_GET['delete_id'];
    
    $stmt = $conn->prepare("DELETE FROM devices WHERE device_id = ?");
    $stmt->bind_param("i", $device_id);
    if ($stmt->execute()) {
        header("Location: device_management.php?delete_success=1");
        exit();
    } else {
        header("Location: device_management.php?delete_error=1");
        exit();
    }
}

// Fetch all devices for display
$query = "SELECT 
            d.device_id, 
            d.device_type, 
            d.mac_address, 
            d.status AS device_status, 
            d.last_seen,
            d.room_id,
            c.Room_code, 
            c.Status AS room_status 
          FROM devices d 
          LEFT JOIN classrooms c ON d.room_id = c.Room_id
          ORDER BY d.device_id DESC";
$result = $conn->query($query);

// Fetch Rooms for the Dropdown - store in array for reuse
$room_query = "SELECT Room_id, Room_code FROM classrooms ORDER BY Room_code ASC";
$rooms_result = $conn->query($room_query);
$rooms = [];
if ($rooms_result->num_rows > 0) {
    while ($room = $rooms_result->fetch_assoc()) {
        $rooms[] = $room;
    }
}

// Fetch specific device data for editing
$edit_device = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM devices WHERE device_id = ?";
    $stmt = $conn->prepare($edit_query);
    if ($stmt) {
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $edit_result = $stmt->get_result();
        if ($edit_result->num_rows > 0) {
            $edit_device = $edit_result->fetch_assoc();
        }
    }
}
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
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>DASHBOARD - Device Management</title>
    <style>
        .mac-address {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .status-online {
            color: #198754;
        }
        .status-offline {
            color: #6c757d;
        }
        .device-type-badge {
            min-width: 100px;
            text-align: center;
        }
        .action-buttons {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <!-- Mobile Toggle Button -->
    <button class="btn btn-primary d-md-none m-2" id="openSidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Mobile Close Button -->
        <div class="sidebar-close d-md-none">
            <button class="btn btn-light btn-sm" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-header text-center">
            <img src="img/loalogo.png" alt="Logo" class="sidebar-logo">
            <h6 class="mt-2 mb-4 text-white">Lyceum of San Pedro</h6>
        </div>

        <ul class="nav flex-column sidebar-menu">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-house"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="rooms.php">
                    <i class="fas fa-door-open"></i> Rooms
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="schedule.php">
                    <i class="fas fa-calendar"></i> Schedule
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="device_management.php">
                    <i class="fa-solid fa-desktop"></i> Device Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="access_logs.php">
                    <i class="fas fa-list"></i> Access Logs
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <small class="text-white">Jonathan M.</small><br>
            <span class="text-light">Faculty Member</span>
            <a href="logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> Log out
            </a>
        </div>
    </div>

    <div class="main-content p-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Device Management</h3>
                <div class="d-flex gap-2 w-100 justify-content-md-end">
                    <div class="input-group room-search" style="max-width: 300px;">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search Device or Room...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end">
            <button class="main-btn mb-4" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                <i class="fas fa-plus me-1"></i> Add Device
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Device Type</th>
                                <th>MAC Address</th>
                                <th>Assigned Room</th>
                                <th>Room Status</th>
                                <th>Device Status</th>
                                <th>Last Seen</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="deviceTableBody">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold">#<?php echo $row['device_id']; ?></td>
                                        <td>
                                            <?php if ($row['device_type'] == 'DOOR'): ?>
                                                <span class="badge bg-info text-dark device-type-badge">
                                                    <i class="fas fa-door-closed me-1"></i> Door/RFID
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark device-type-badge">
                                                    <i class="fas fa-bolt me-1"></i> Power
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="mac-address"><?php echo htmlspecialchars($row['mac_address']); ?></span>
                                        </td>
                                        <td>
                                            <?php echo $row['Room_code'] ? htmlspecialchars($row['Room_code']) : '<span class="text-muted fst-italic">Unassigned</span>'; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['room_status'] == 'Occupied'): ?>
                                                <span class="badge bg-danger">Occupied</span>
                                            <?php elseif ($row['room_status'] == 'Unoccupied'): ?>
                                                <span class="badge bg-success">Unoccupied</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['device_status'] == 'Online'): ?>
                                                <span class="status-badge status-online">
                                                    <i class="fas fa-circle fa-xs"></i> Online
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-offline">
                                                    <i class="fas fa-circle fa-xs"></i> Offline
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo date('M d, h:i A', strtotime($row['last_seen'])); ?>
                                        </td>
                                        <td class="text-end pe-4 action-buttons">
                                            <a href="device_management.php?edit_id=<?php echo $row['device_id']; ?>" 
                                               class="btn btn-sm btn-outline-primary me-1" 
                                               title="Edit Device">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-id="<?php echo $row['device_id']; ?>"
                                                    data-mac="<?php echo htmlspecialchars($row['mac_address']); ?>"
                                                    data-type="<?php echo $row['device_type'] == 'DOOR' ? 'Door/RFID' : 'Power Control'; ?>"
                                                    title="Delete Device">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-desktop fa-2x mb-3 d-block"></i>
                                        No devices found. Add your first device to get started.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Device Modal -->
    <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="addDeviceForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Device Type <span class="text-danger">*</span></label>
                            <select name="device_type" class="form-select" required>
                                <option value="">Select Device Type</option>
                                <option value="DOOR">RFID Door Lock</option>
                                <option value="POWER">Power Control</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Assign to Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <option value="">Select a Room...</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['Room_id']; ?>">
                                        <?php echo htmlspecialchars($room['Room_code']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Available rooms are fetched from the Rooms page.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">MAC Address <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="mac_address" 
                                   class="form-control mac-input" 
                                   placeholder="XX:XX:XX:XX:XX:XX" 
                                   required>
                            <div class="form-text">Format: XX:XX:XX:XX:XX:XX (example: D4:E9:F4:65:F5:1C)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_device" class="main-btn">
                            <i class="fas fa-save me-1"></i> Save Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Device Modal - This will show when edit_id is in URL -->
    <?php if ($edit_device): ?>
    <div class="modal fade" id="editDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Device #<?php echo $edit_device['device_id']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="device_id" value="<?php echo $edit_device['device_id']; ?>">
                    <div class="modal-body">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 fs-5"></i>
                            <div>Editing device configuration. Changes will take effect immediately.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Device Type <span class="text-danger">*</span></label>
                            <select name="device_type" class="form-select" required>
                                <option value="">Select Device Type</option>
                                <option value="DOOR" <?php echo ($edit_device['device_type'] == 'DOOR') ? 'selected' : ''; ?>>RFID Door Lock</option>
                                <option value="POWER" <?php echo ($edit_device['device_type'] == 'POWER') ? 'selected' : ''; ?>>Power Control</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Assign to Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <option value="">Select a Room...</option>
                                <?php foreach ($rooms as $room): ?>
                                    <?php $selected = ($room['Room_id'] == $edit_device['room_id']) ? 'selected' : ''; ?>
                                    <option value="<?php echo $room['Room_id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($room['Room_code']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">MAC Address <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="mac_address" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($edit_device['mac_address']); ?>" 
                                   placeholder="XX:XX:XX:XX:XX:XX" 
                                   required>
                            <div class="form-text">Format: XX:XX:XX:XX:XX:XX</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Device Status</label>
                                    <div class="form-control-plaintext">
                                        <?php if ($edit_device['status'] == 'Online'): ?>
                                            <span class="status-badge status-online">
                                                <i class="fas fa-circle fa-xs"></i> Online
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-offline">
                                                <i class="fas fa-circle fa-xs"></i> Offline
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Seen</label>
                                    <div class="form-control-plaintext">
                                        <?php echo date('M d, h:i A', strtotime($edit_device['last_seen'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_device" class="main-btn">
                            <i class="fas fa-save me-1"></i> Update Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show success/error messages
        <?php if (isset($_GET['edit_success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Device updated successfully.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        <?php if (isset($_GET['edit_error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to update device. Please try again.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        <?php if (isset($_GET['add_success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Device added successfully.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        <?php if (isset($_GET['add_error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to add device. Please try again.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        <?php if (isset($_GET['delete_success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Device deleted successfully.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        <?php if (isset($_GET['delete_error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to delete device. Please try again.',
            timer: 3000,
            showConfirmButton: false
        });
        <?php endif; ?>

        // Show edit modal if edit_id is in URL
        <?php if (isset($_GET['edit_id']) && $edit_device): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = new bootstrap.Modal(document.getElementById('editDeviceModal'));
            editModal.show();
        });
        <?php endif; ?>

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchText = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#deviceTableBody tr');

            tableRows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });

        // Delete confirmation with SweetAlert
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const deviceId = this.getAttribute('data-id');
                const macAddress = this.getAttribute('data-mac');
                const deviceType = this.getAttribute('data-type');
                
                Swal.fire({
                    title: 'Delete Device?',
                    html: `<div class="text-start">
                           <p>Are you sure you want to delete this device?</p>
                           <div class="alert alert-warning p-3 mt-2">
                               <div class="fw-bold">Device Details:</div>
                               <div class="mt-2">
                                   <div><strong>MAC Address:</strong> ${macAddress}</div>
                                   <div><strong>Type:</strong> ${deviceType}</div>
                               </div>
                           </div>
                           <p class="text-danger mt-2"><small><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</small></p>
                           </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    width: '500px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'device_management.php?delete_id=' + deviceId;
                    }
                });
            });
        });

        // Auto-refresh with scroll preservation
        window.onbeforeunload = function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        };

        document.addEventListener("DOMContentLoaded", function() {
            var scrollPos = localStorage.getItem('scrollPosition');
            if (scrollPos) {
                window.scrollTo(0, scrollPos);
            }
            
            // Auto-Refresh the page every 10 seconds
            setInterval(function(){
                location.reload();
            }, 10000);
        });

        // MAC address formatting (optional)
        document.querySelectorAll('input[name="mac_address"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^A-Fa-f0-9]/g, '').toUpperCase();
                if (value.length > 12) value = value.substring(0, 12);
                
                let formatted = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 2 === 0) formatted += ':';
                    formatted += value[i];
                }
                e.target.value = formatted;
            });
        });

        // Remove edit_id from URL when edit modal is closed
        const editModalElement = document.getElementById('editDeviceModal');
        if (editModalElement) {
            editModalElement.addEventListener('hidden.bs.modal', function () {
                // Remove edit_id from URL without reloading
                const url = new URL(window.location);
                url.searchParams.delete('edit_id');
                window.history.replaceState({}, '', url);
            });
        }
    </script>

    <!-- Your Local JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>