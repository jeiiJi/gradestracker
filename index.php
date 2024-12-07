<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'grade_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects for the dropdown or buttons
$subjects = $conn->query("SELECT * FROM subjects");

// Get the selected subject or use a default
$selected_subject = isset($_GET['subject_id']) ? $_GET['subject_id'] : 0;

// Fetch the assignments for the selected subject
if ($selected_subject) {
    $stmt = $conn->prepare("SELECT * FROM assignments WHERE subject_id = ?");
    $stmt->bind_param("i", $selected_subject);
    $stmt->execute();
    $assignments = $stmt->get_result();
} else {
    $assignments = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
        input[type="number"] { width: 90%; padding: 5px; }
        .subject-buttons { text-align: center; margin: 20px; }
        .subject-buttons button { padding: 10px 20px; margin: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="subject-buttons">
    <!-- Buttons for subjects -->
    <button onclick="window.location.href='index.php'">Summary</button>
    <?php while ($subject = $subjects->fetch_assoc()) { ?>
        <button onclick="window.location.href='index.php?subject_id=<?= $subject['id'] ?>'">
            <?= $subject['subject_name'] ?>
        </button>
    <?php } ?>
</div>

<!-- Display Table -->
<table>
    <thead>
        <tr>
            <th colspan="3">
                <?= $selected_subject ? $conn->query("SELECT subject_name FROM subjects WHERE id = $selected_subject")->fetch_assoc()['subject_name'] : 'Tracker' ?>
            </th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Points</th>
            <th>Grade (%)</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($assignments): ?>
            <?php while ($assignment = $assignments->fetch_assoc()): ?>
                <tr>
                    <td><?= $assignment['name'] ?></td>
                    <td>
                        <input 
                            type="number" 
                            data-id="<?= $assignment['id'] ?>" 
                            value="<?= $assignment['points'] ?>" 
                            oninput="updateGrade(this)"
                        >
                    </td>
                    <td id="grade-<?= $assignment['id'] ?>">
                        <?= $assignment['grade'] !== null ? $assignment['grade'] . '%' : '' ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">INSTRUCTIONS: Please select your chosen subject to track grades.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    // Update grade using AJAX
    function updateGrade(input) {
        const id = input.dataset.id;
        const points = input.value;

        // Send data to update_grade.php
        fetch('update_grade.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, points })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`grade-${id}`).textContent = `${data.grade}%`;
            } else {
                alert('Failed to update grade.');
            }
        });
    }
</script>

</body>
</html>
