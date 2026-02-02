<?php
// Include your database connection file (adjust path as needed)
include 'conn.php';

// Handle "Add Device" Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_device'])) {
    $mac_address = $_POST['mac_address']; // Just for connection as you mentioned
    $room_id = $_POST['room_id'];
    $device_type = $_POST['device_type'];

    $stmt = $conn->prepare("INSERT INTO devices (mac_address, room_id, device_type, status) VALUES (?, ?, ?, 'Offline')");
    $stmt->bind_param("sis", $mac_address, $room_id, $device_type);
    $stmt->execute();
    header("Location: devices_management.php"); // Refresh to prevent resubmission
    exit();
}

// Fetch Devices with Room Details using JOIN
// We LEFT JOIN classrooms so we can see the Room Code and Room Status alongside the device
$query = "SELECT 
            d.device_id, 
            d.device_type, 
            d.mac_address, 
            d.status AS device_status, 
            d.last_seen,
            c.Room_code, 
            c.Status AS room_status 
          FROM devices d 
          LEFT JOIN classrooms c ON d.room_id = c.Room_id
          ORDER BY d.device_id DESC";
$result = $conn->query($query);

// Fetch Rooms for the Dropdown (This ensures the dropdown always reflects current rooms)
$room_query = "SELECT Room_id, Room_code FROM classrooms ORDER BY Room_code ASC";
$rooms_result = $conn->query($room_query);
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
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>DASHBOARD</title>
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
    <!-- REUSABLE UNTIL HERE PARA SA SIDE BAR -->

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
                                                <span class="badge bg-info text-dark"><i class="fas fa-door-closed me-1"></i> Door/RFID</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="fas fa-bolt me-1"></i> Power</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['Room_code'] ? $row['Room_code'] : '<span class="text-muted fst-italic">Unassigned</span>'; ?>
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
                                                <span class="text-success"><i class="fas fa-circle fa-xs me-1"></i> Online</span>
                                            <?php else: ?>
                                                <span class="text-secondary"><i class="fas fa-circle fa-xs me-1"></i> Offline</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo date('M d, h:i A', strtotime($row['last_seen'])); ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No devices found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Device Type</label>
                            <select name="device_type" class="form-select" required>
                                <option value="DOOR">RFID Door Lock</option>
                                <option value="POWER">Power Control</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign to Room</label>
                            <select name="room_id" class="form-select" required>
                                <option value="">Select a Room...</option>
                                <?php
                                if ($rooms_result->num_rows > 0) {
                                    while ($room = $rooms_result->fetch_assoc()) {
                                        echo "<option value='" . $room['Room_id'] . "'>" . $room['Room_code'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div class="form-text">Available rooms are fetched from the Room Page.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">MAC Address (RFID Connection)</label>
                            <input type="text" name="mac_address" class="form-control" placeholder="XX:XX:XX:XX:XX:XX" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_device" class="main-btn">Save Device</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchText = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#deviceTableBody tr');

            tableRows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>