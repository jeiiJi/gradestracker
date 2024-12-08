<?php
$conn = new mysqli('localhost', 'root', '', 'grades_tracker');
$id = $_POST['id'];
$stmt = $conn->prepare("SELECT file_path FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo $row['file_path'];
}
?>
