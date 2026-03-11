<?php
// include "conn.php";
include "session_auth.php";
// Get the current filename to set the active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<button class="btn btn-primary d-md-none m-2" id="openSidebar">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">

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
            <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                <i class="fas fa-house"></i> Home
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'users.php') ? 'active' : ''; ?>" href="users.php">
                <i class="fas fa-users"></i> Users
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'rooms.php') ? 'active' : ''; ?>" href="rooms.php">
                <i class="fas fa-door-open"></i> Rooms
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'schedule.php') ? 'active' : ''; ?>" href="schedule.php">
                <i class="fas fa-calendar"></i> Schedule
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'device_management.php') ? 'active' : ''; ?>" href="device_management.php">
                <i class="fa-solid fa-desktop"></i> Device Management
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'access_logs.php') ? 'active' : ''; ?>" href="access_logs.php">
                <i class="fas fa-list"></i> Access Logs
            </a>
        </li>
    </ul>

<div class="sidebar-footer">
    <small class="text-white">
        <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?>
    </small><br>

    <span class="text-light">Administrator</span>

    <a href="logout.php" class="logout">
        <i class="fas fa-sign-out-alt"></i> Log out
    </a>
</div>
</div>