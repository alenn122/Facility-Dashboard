<?php
include 'conn.php';
header('Content-Type: application/json');

$sql = "SELECT u.*, cs.CourseSection, u.courseSection_id as cs_id 
        FROM users u 
        LEFT JOIN course_section cs ON u.courseSection_id = cs.CourseSection_id 
        ORDER BY u.User_id DESC";
$result = mysqli_query($conn, $sql);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);
?>