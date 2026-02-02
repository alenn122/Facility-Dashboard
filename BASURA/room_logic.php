<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // LINK NEW DEVICE TO ROOM
    if (isset($_POST['add_device'])) {
        $deviceId = $_POST['device_id'];
        $roomId = $_POST['room_id'];
        $type = $_POST['device_type'];

        $stmt = $conn->prepare("INSERT INTO devices (device_id, room_id, device_type, device_status, last_seen) VALUES (?, ?, ?, 'active', NOW())");
        $stmt->bind_param("sis", $deviceId, $roomId, $type);
        $stmt->execute();
        header("Location: devices.php");
        exit();
    }

    // TOGGLE STATUS + HEARTBEAT
    if (isset($_POST['toggle_status'])) {
        $deviceId = $_POST['device_id'];

        // Update room status and refresh the 'last_seen' timestamp
        $sql = "UPDATE classrooms 
                JOIN devices ON classrooms.Room_id = devices.room_id 
                SET classrooms.Status = IF(classrooms.Status = 'Occupied', 'Unoccupied', 'Occupied'),
                    devices.last_seen = NOW() 
                WHERE devices.device_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $deviceId);
        $stmt->execute();
        header("Location: devices.php");
        exit();
    }
}
?>