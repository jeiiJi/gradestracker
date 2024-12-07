<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON output
$response = ['success' => false, 'message' => 'Something went wrong'];

try {
    $conn = new mysqli('localhost', 'root', '', 'grade_tracker');
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['id']) || !isset($data['points'])) {
        throw new Exception("Invalid input");
    }

    $id = $data['id'];
    $points = $data['points'];

    $query = $conn->query("SELECT max_points FROM assignments WHERE id = $id");
    if ($query->num_rows === 0) {
        throw new Exception("Assignment not found");
    }

    $max_points = $query->fetch_assoc()['max_points'];
    if ($points < 0 || $points > $max_points) {
        throw new Exception("Points out of range");
    }

    $grade = ($points / $max_points) * 100;
    $stmt = $conn->prepare("UPDATE assignments SET points = ?, grade = ? WHERE id = ?");
    $stmt->bind_param("dii", $points, $grade, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['grade'] = $grade;
    } else {
        throw new Exception("Failed to update database");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);


$conn = new mysqli('localhost', 'root', '', 'grade_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the request
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$points = $data['points'];

// Retrieve max_points for validation
$query = $conn->query("SELECT max_points FROM assignments WHERE id = $id");
$max_points = $query->fetch_assoc()['max_points'];

// Validate input
if ($points < 0 || $points > $max_points) {
    echo json_encode(['success' => false, 'message' => 'Invalid points']);
    exit;
}

// Calculate grade
$grade = ($points / $max_points) * 100;

// Update database
$stmt = $conn->prepare("UPDATE assignments SET points = ?, grade = ? WHERE id = ?");
$stmt->bind_param("dii", $points, $grade, $id);
$response = $stmt->execute();

echo json_encode(['success' => $response, 'grade' => $grade]);
?>
