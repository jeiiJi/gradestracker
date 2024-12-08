<?php
$courses = scandir('courses/'); // Directory where course files are stored
$course_titles = array_filter($courses, function ($course) {
    return !in_array($course, ['.', '..']);
});

foreach ($course_titles as $index => $course) {
    echo "<div class='course_item' data-index='$index'>$course</div>";
}
?>


