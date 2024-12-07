<?php
// Process grading weights
$use_default = isset($_GET['grading-option']) && $_GET['grading-option'] === 'default';
if ($use_default) {
    // Default weights
    $performance_task = 50;
    $written_work = 25;
    $assessment = 25;
} else {
    // Custom weights from input
    $performance_task = isset($_GET['performance-task']) ? (float)$_GET['performance-task'] : 0;
    $written_work = isset($_GET['written-work']) ? (float)$_GET['written-work'] : 0;
    $assessment = isset($_GET['assessment']) ? (float)$_GET['assessment'] : 0;

    // Validate weights sum to 100 only if custom weights are used
    $total_weight = $performance_task + $written_work + $assessment;

    if (abs($total_weight - 100) > 0.01) {
        die('Error: The total percentage must equal 100.');
    }
}

// Display weights (for debugging purposes)
if ($use_default) {
    echo "Using default weights: Performance Task: $performance_task%, Written Work: $written_work%, Assessment: $assessment%<br>";
} else {
    echo "Custom weights: Performance Task: $performance_task%, Written Work: $written_work%, Assessment: $assessment%. Total: " . number_format($total_weight, 2) . "%<br>";
}
?>