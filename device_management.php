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
            <div class="input-group room-search">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search Device, ID, Course...">
            </div>
        </div>
    </div>
    </div>