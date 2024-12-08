<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'grades_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'create') {
    // Create a new course
    $course_name = $_POST['course_name'];
    $is_default = $_POST['is_default'];

    // Generate file path
    $file_name = preg_replace('/\s+/', '_', strtolower($course_name)) . '.html';
    $file_path = "courses/$file_name";

    // Default content
    $default_content = $is_default === 'true'
        ? "<h1>$course_name</h1>\n<p>This is a default course file.</p>"
        : "";

    // Create file
    if (file_put_contents($file_path, $default_content) !== false) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO courses (course_name, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $course_name, $file_path);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Course created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create file']);
    }
} elseif ($action === 'delete') {
    // Delete a course
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT file_path FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        unlink($row['file_path']); // Delete the file
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Course deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
    }
} elseif ($action === 'rename') {
    // Rename a course
    $id = $_POST['id'];
    $new_name = $_POST['new_name'];
    $stmt = $conn->prepare("SELECT file_path FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $old_path = $row['file_path'];
        $new_path = "courses/" . preg_replace('/\s+/', '_', strtolower($new_name)) . '.html';
        if (rename($old_path, $new_path)) {
            $stmt = $conn->prepare("UPDATE courses SET course_name = ?, file_path = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_name, $new_path, $id);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Course renamed successfully']);
        } else { 
            echo json_encode(['success' => false, 'message' => 'Failed to rename file']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
    }
}
?>