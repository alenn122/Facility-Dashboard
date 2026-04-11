<?php
include 'conn.php';

echo "<h2>Testing Archive System</h2>";

// Check users table structure
echo "<h3>Users Table Structure:</h3>";
$result = mysqli_query($conn, "DESCRIBE users");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Show all users and their status
echo "<h3>All Users:</h3>";
$result = mysqli_query($conn, "SELECT User_id, F_name, L_name, Status, archived_date FROM users ORDER BY User_id");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Archived Date</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['User_id'] . "</td>";
    echo "<td>" . $row['F_name'] . " " . $row['L_name'] . "</td>";
    echo "<td>" . $row['Status'] . "</td>";
    echo "<td>" . ($row['archived_date'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Show archived users count
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE Status = 'Archived'");
$row = mysqli_fetch_assoc($result);
echo "<h3>Archived Users Count: " . $row['count'] . "</h3>";

$conn->close();
?>