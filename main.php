<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Grades Tracker Main Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--links of logos-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
 
    <!--Links for the css files-->
    <link rel="stylesheet" href="style.css">
</head>

<body>
<!--BACKGROUND PHOTO-->
<section class="all_backgrounds_container"> 
<img id="background_photo" src="pictures/unc_sp_bg.jpg" alt=" ">
<div class="site_title">
    <p>Grades Tracker</p>
</div>
</section>


<section class="main_container">
    <div class="main_contents_background">
        <div class="maincontentbg_highlight"></div>
        <main>
            <div class="main_title_section">
                <div class="main_course_title" onclick="location.href='main.html'">
                    <h1>COURSE</h1>
                </div>
            </div>

            <div class="filter_container">
                <section class="filter">Sort by:</label>
                    <select name="filter" id="filter">
                      <option value="ascending-order">Alphabetical (A to Z)</option>
                      <option value="descneding-order">Alphabetical (Z to A)</option>
                    </select>
                  </section>

                <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "your_database";
                    
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    // Determine sorting order based on filter
                    $order = "ASC"; // Default sorting
                    if (isset($_GET['filter']) && $_GET['filter'] == 'desc') {
                        $order = "DESC";
                    }
                    
                    $sql = "SELECT * FROM files ORDER BY title $order";
                    $result = $conn->query($sql);
                ?>

                <script>
                    document.getElementById('filter').addEventListener('change', function() {
                    document.getElementById('filter_form').submit();
                    });
                </script>
            </div>

            <div class="course_item_container">
                <div class="course_title_container" onclick="location.href='http://localhost/gradestracker/index.php?subject_id=1'">
                    <h3 class="course_title">Math</h3>
                    <div class="dropdown">
                        <button class="dropbtn">...</button>
                        <div class="dropdown-content">
                            <button onclick="event.stopPropagation(); renameCourse(1)">Rename</button>
                            <button onclick="event.stopPropagation(); deleteCourse(1)">Delete</button>
                            <button onclick="event.stopPropagation(); window.open('course_file_path.html', '_blank')">Open</button>
                      
                            <div class="course_title_container" onclick="location.href='http://localhost/gradestracker/index.php?subject_id=2'">
                                <h3 class="course_title">Science</h3>
                                <div class="dropdown">
                                    <button class="dropbtn">...</button>
                                    <div class="dropdown-content">
                                        <button onclick="event.stopPropagation(); renameCourse(1)">Rename</button>
                                        <button onclick="event.stopPropagation(); deleteCourse(1)">Delete</button>
                                        <button onclick="event.stopPropagation(); window.open('course_file_path.html', '_blank')">Open</button>
                                    </div>
                                </div>
                            </div>
                </div>
                        <script src="course_handle_button.js"></script>

                        <div class="course_list">
                            <div id="course_items_container"></div>
                            <div class="pagination_controls">
                                <button id="prev_page" disabled>Previous</button>
                                <span id="page_indicator">Page 1</span>
                                <button id="next_page">Next</button>
                            </div>
                        </div>
                    </div>

                    <?php
                    $courses = scandir('courses/'); // Directory where course files are stored
                    $course_titles = array_filter($courses, function ($course) {
                        return !in_array($course, ['.', '..']);
                    });

                    foreach ($course_titles as $index => $course) {
                        echo "<div class='course_item' data-index='$index'>$course</div>";
                    }
                    ?>
            </div>

            <button id="myBtn" onclick="location.href='create_new_file.html'">+</button></a>
        </main>
    </div>
</section>
</body>
</html>