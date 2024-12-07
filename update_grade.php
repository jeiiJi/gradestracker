<?php
$conn = new mysqli('localhost', 'root', '', 'grade_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the request
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$points = $data['points'];

// Calculate grade percentage
$grade = ($points > 0) ? ($points / 100) * 100 : 0;

// Update the database
$stmt = $conn->prepare("UPDATE assignments SET points = ?, grade = ? WHERE id = ?");
$stmt->bind_param("dii", $points, $grade, $id);
$response = $stmt->execute();

// Send response
echo json_encode(['success' => $response, 'grade' => $grade]);
?>
