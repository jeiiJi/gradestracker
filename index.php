<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'grade_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses for the dropdown or buttons
$courses = $conn->query("SELECT * FROM courses");

// Get the selected course or use a default
$selected_course = isset($_GET['course_id']) ? $_GET['course_id'] : 0;

// Fetch the assignments for the selected subject
if ($selected_course) {
    $stmt = $conn->prepare("SELECT * FROM assignments WHERE course_id = ?");
    $stmt->bind_param("i", $selected_subject);
    $stmt->execute();
    $assignments = $stmt->get_result();
} else {
    $assignments = null;
}

// Get performance task criteria and weights from database
$criteria = $conn->query("SELECT * FROM performance_task_criteria");

if (!$criteria) {
    die("Error fetching performance task criteria: " . $conn->error);
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
        table { width: 50%; margin: 50px; border-collapse: collapse; left:40px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
        input[type="number"] { width: 90%; padding: 5px; }
        .course-buttons { text-align: center; margin: 20px; }
        .course-buttons button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .points_columnHeader { width: 15%; }
        .name_columnHeader { width: 70%; }
        .grade_columnHeader { width: 15%; }


        /*NAVBAR CSS*/
        /* Navbar styling */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px; /* Set a specific height */
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            background-color: #333; /* Add a background color */
        }

        /* Return button styling */
        .return-button {
            color: rgb(0, 0, 0);
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            background-color: #555555;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Dropdown styling */
        .dropdown {
            position: relative;
            display: inline-block;
            right: 0;
        }

        .dropdown select {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #444;
            color: #000000;
            appearance: none;
            outline: none;
        }

        .dropdown select:hover {
            background-color: #555;
        }

        .whole_tracker_container {position: fixed; margin-top: 120px; padding-left: 30px;}
        .course_subnavbar_container {
            position: fixed;
            margin-top: 60px; 
            top: 0; 
            background-color: #000000; 
            width: 100%;
            text-align: center;
            left: 0;
            height: 70px;
        }

        .performanceTaskTracker_table_container th, .performanceTaskTracker_table_container td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .performanceTaskTracker_table_container h1{
            position: fixed;
            top: 130px;
        }
        .mainTracker_table_container{
            position: fixed;
            left: 0;
            height: 100%;
            width: 50%;
        }
        .performanceTaskTracker_table_container{
            position: fixed;
            right: 0;
            height: 100%;
            width: 50%;
            top: 150px;
            align-items: center;
        }

        .mainTracker_table_container h1 {margin: 50px 0 0 55px;}
    </style>
</head>
<body>
<section class="navbar_container">
<!-- Navbar -->
<div class="navbar">
    <!-- Return Button -->
    <button class="return-button" onclick="location.href='//localhost/gradestracker/main.html'">◀</button>

    <!-- Dropdown for student names -->
    <div class="dropdown" style="margin-right: 50px;">
        <select id="student-dropdown" onchange="redirectToTracker()">
            <option value="">Select a Student</option>
            <option value="index.php?student_id=1">John Doe</option>
            <option value="index.php?student_id=2">Jane Doe</option>
            <option value="index.php?student_id=3">Christine Joy Morata</option>
        </select>
    </div>
</div>

<script>
    // JavaScript to handle dropdown redirection
    function redirectToTracker() {
        const dropdown = document.getElementById('student-dropdown');
        const selectedValue = dropdown.value;

        if (selectedValue) {
            window.location.href = selectedValue; // Redirect to the selected tracker's page
        }
    }
</script>
<section class="course_subnavbar_container">
    <div class="course-buttons">
        <!-- Button for Tracker Guide -->
        <button onclick="window.location.href='index.php'">Tracker Guide</button>
        
        <?php 
// Fetch all courses
$courses = $conn->query("SELECT * FROM courses");

// Check if courses are available
if ($courses && $courses->num_rows > 0) {
    while ($course = $courses->fetch_assoc()) {
        ?>
        <!-- Button for each course -->
        <button onclick="window.location.href='index.php?course_id=<?= $course['id'] ?>'" class="course_button_href">
            <?= htmlspecialchars($course['course_name']) ?>
        </button>
        <?php
    }
} else {
    // Message if no courses are found
    echo "<p>No courses found.</p>";
}
?>
    </div>
</section>

<section class="whole_tracker_container">
<!-- FIRST TABLE / MAIN TRACKER -->
<div class="mainTracker_table_container">
<h1>Tracker</h1>
<table>
    <thead>
        <tr>
            <th colspan="3">
                
            <?= $selected_course ? ($conn->query("SELECT course_name FROM courses WHERE id = $selected_course")->fetch_assoc()['course_name'] ?? 'Unknown Course') : 'Tracker' ?>
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

<!--PROCESS OF GRADES OF MAIN TRACKER TABLE-->
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
</div>

<!-- SECOND TABLE / CRITERIA TRACKER -->
<div class="performanceTaskTracker_table_container">
    <h1>Performance Task Tracker</h1>
    <table>
        <thead>
            <tr>
                <th>Criteria</th>
                <?php while ($criterion = $criteria->fetch_assoc()) { ?>
                    <th><?= $criterion['name'] ?> (<?= $criterion['weight'] ?>%)</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 4; $i++) { 
                $criteria->data_seek(0); // Reset cursor to the beginning for each row
            ?>
                <tr>
                    <td>Row <?= $i + 1 ?></td>
                    <?php while ($criterion = $criteria->fetch_assoc()) { ?>
                        <td>
                            <input type="checkbox" data-weight="<?= $criterion['weight'] ?>">
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <script src="performance_tracker_logic.js"></script>
</div>

</section>



</body>
</html>
