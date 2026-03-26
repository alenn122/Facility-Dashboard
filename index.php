<?php
include "conn.php";
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
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>DASHBOARD</title>
</head>

<body>
    <!-- NAVIGATION SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <div class="main-content p-4">
        <h3 class="fw-bold mb-2">Dashboard</h3>

        <?php
        $totalroom = $conn->query("SELECT COUNT(*) AS total FROM classrooms")->fetch_assoc();
        $totaloccupied = $conn->query("SELECT COUNT(*) AS total FROM classrooms WHERE status = 'occupied'")->fetch_assoc();
        $totalUnoccupied = $conn->query("SELECT COUNT(*) AS total FROM classrooms WHERE status = 'Unoccupied'")->fetch_assoc();
        $totalusers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc();
        ?>

        <!-- Dashboard Cards -->
        <div class="row g-4 mb-4">

            <div class="col-lg-3 col-md-6" href="rooms.php">
                <div class="dashboard-card">
                    <h2><?php echo $totalroom['total']; ?></h2>
                    <p>Total Rooms</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" href="users.php">
                <div class="dashboard-card">
                    <h2><?php echo $totalusers['total']; ?></h2>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card">
                    <h2><?php echo $totaloccupied['total']; ?></h2>
                    <p>Occupied Rooms</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card">
                    <h2><?php echo $totalUnoccupied['total']; ?></h2>
                    <p>Unoccupied Rooms</p>
                </div>
            </div>

        </div>

        <!-- Recent Access -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <span class="badge bg-primary">Recent Access</span>
                </h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Room</th>
                                <th>Access_Type</th>
                                <th>Access_time</th>
                                <th>Access</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="accessLogsTableBody">

                            <?php
                            $log_id = $conn->query("
                            SELECT 
                                    u.F_name,
                                    u.L_name,
                                    al.Rfid_tag,
                                    al.Access_time,
                                    al.Access_type,
                                    al.Status,
                                    u.Role,
                                    r.Room_code,
                                   al.device_type
                                FROM access_log al
                                LEFT JOIN users u ON al.User_id = u.User_id
                                LEFT JOIN classrooms r ON al.Room_id = r.Room_id
                                ORDER BY al.Access_time DESC
                                LIMIT 10
                        ");
                            ?>

                            <?php if ($log_id->num_rows > 0): ?>
                             
                                <?php while ($row = $log_id->fetch_assoc()): ?>
                                    <tr>
                                        

                                        <td><strong><?php echo $row['F_name'] . ' ' . $row['L_name'];?></strong></td>
                                        <td><?php echo $row['Role']; ?></td>
                                        <td><?php echo $row['Room_code']; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $row['device_type']; ?></span></td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($row['Access_time'])); ?></td>
                                        <td><?php echo $row['Access_type']; ?></td>
                                        <td>
                                            <span class="status <?php echo strtolower($row['Status']); ?>">
                                                <?php echo $row['Status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No recent access records found.</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>











    <!-- JAVASCRIPT -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        function updateAccessLogs() {
            fetch('ajax/get_access_logs.php?limit=10')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('accessLogsTableBody');
                        tbody.innerHTML = '';
                        
                        if (data.logs.length > 0) {
                            data.logs.forEach(log => {
                                let displayName = "Guest";
                                if (log.F_name || log.L_name) {
                                    displayName = `${log.F_name || ''} ${log.L_name || ''}`.trim();
                                } else if (log.Rfid_tag) {
                                    displayName = `Unknown (${log.Rfid_tag})`;
                                }
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                                                                                        
                                    <td><strong>${displayName}</strong></td>
                                    <td>${log.Role}</td>
                                    <td>${log.Room_code}</td>
                                    <td><small class="badge bg-secondary">${log.device_type || 'N/A'}</small></td> 
                                    <td>${log.Access_time}</td>
                                    <td>${log.Access_type}</td>
                                    <td><span class="status ${log.Status.toLowerCase()}">${log.Status}</span></td>
                                `;
                                tbody.appendChild(row);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No records found.</td></tr>';
                        }
                    }
                })
                .catch(error => console.error('Error fetching access logs:', error));
        }

        // Update every 5 seconds
        setInterval(updateAccessLogs, 5000);

        // Initial load is already done by PHP
    </script>
</body>

</html>