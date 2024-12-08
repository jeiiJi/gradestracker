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
        table { width: 60%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
        input[type="number"] { width: 90%; padding: 5px; }
        .subject-buttons { text-align: center; margin: 20px; }
        .subject-buttons button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .points_columnHeader { width: 15%; }
        .name_columnHeader { width: 70%; }
        .grade_columnHeader { width: 15%; }
    </style>
</head>
<body>
<?php 
include 'tracker_navbar.html';
?>

<div class="subject-buttons">
    <!-- Buttons for subjects -->
    <button onclick="window.location.href='index.php'">Tracker Guide</button>
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
            <th class="name_columnHeader">Name</th>
            <th class="points_columnHeader">Points</th>
            <th class="grade_columnHeader">Grade (%)</th>
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
            <!-- Row for Overall Grade -->
            <tr>
                <td colspan="2"><strong>Overall Grade</strong></td>
                <td id="overall-grade">0%</td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="3">INSTRUCTIONS: Please select your chosen subject to track grades.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    // Update grade and calculate overall grade using AJAX
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
                calculateOverallGrade();
            } else {
                alert('Failed to update grade.');
            }
        });
    }

    // Calculate the overall grade based on weights
    function calculateOverallGrade() {
        const rows = document.querySelectorAll("tbody tr");
        let totalGrade = 0;
        let writtenWorkGrade = 0, performanceTaskGrade = 0, assessmentGrade = 0;

        rows.forEach(row => {
            const name = row.cells[0]?.textContent;
            const gradeCell = row.cells[2];
            const grade = parseFloat(gradeCell?.textContent) || 0;

            if (name === "Written Work") writtenWorkGrade = grade * 0.25;
            if (name === "Performance Task") performanceTaskGrade = grade * 0.50;
            if (name === "Assessment") assessmentGrade = grade * 0.25;
        });

        totalGrade = writtenWorkGrade + performanceTaskGrade + assessmentGrade;
        document.getElementById("overall-grade").textContent = `${totalGrade.toFixed(2)}%`;
    }
</script>

</body>
</html>
