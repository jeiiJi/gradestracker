<?php
$conn = new mysqli('localhost', 'root', '', 'grades_tracker');
$result = $conn->query("SELECT id, course_name FROM courses");
$courses = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($courses);
?>
