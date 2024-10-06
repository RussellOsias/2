<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Database</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Navigation</h2>
            <ul>
                <li><a href="index.php?page=instructor">Instructor</a></li>
                <li><a href="index.php?page=department">Department</a></li>
                <li><a href="index.php?page=course">Course</a></li>
                <li><a href="index.php?page=classroom">Classroom</a></li>
                <li><a href="index.php?page=section">Section</a></li>
                <li><a href="index.php?page=time_slot">Time Slot</a></li>
                <li><a href="index.php?page=student">Student</a></li>
            </ul>
        </div>

        <div class="content">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                include $page . '.php'; // Includes the corresponding PHP file based on the page selected
            } else {
                echo "<h2>Welcome to the University Database</h2>";
            }
            ?>
        </div>
    </div>
</body>
</html>
