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