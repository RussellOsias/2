<?php
include 'includes/db.php';

// Handle create operation if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Section
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
        $sec_id = mysqli_real_escape_string($conn, $_POST['sec_id']);
        $building = mysqli_real_escape_string($conn, $_POST['building']);
        $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
        $time_slot_id = mysqli_real_escape_string($conn, $_POST['time_slot_id']);
        $semester = mysqli_real_escape_string($conn, $_POST['semester']);
        $year = mysqli_real_escape_string($conn, $_POST['year']);

        $query = "INSERT INTO section (course_id, sec_id, building, room_number, time_slot_id, semester, year) 
                  VALUES ('$course_id', '$sec_id', '$building', '$room_number', '$time_slot_id', '$semester', '$year')";

        if (mysqli_query($conn, $query)) {
            echo "<p style='color:green;'>Section added successfully.</p>";
        } else {
            echo "<p style='color:red;'>Error adding section: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Fetch sections along with course titles and classroom details
$query = "SELECT s.*, c.title AS course_title, cl.building, cl.room_number 
          FROM section s
          JOIN course c ON s.course_id = c.course_id
          JOIN classroom cl ON s.building = cl.building AND s.room_number = cl.room_number"; 

$result = mysqli_query($conn, $query);
?>

<h2>Sections</h2>

<!-- Display Sections -->
<table border="1">
    <thead>
        <tr>
            <th>Course ID</th>
            <th>Course Title</th>
            <th>Section ID</th>
            <th>Building</th>
            <th>Room Number</th>
            <th>Time Slot ID</th>
            <th>Semester</th>
            <th>Year</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['sec_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['building']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_slot_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                    <td>
                        <a href="edit_section.php?course_id=<?php echo htmlspecialchars($row['course_id']); ?>&sec_id=<?php echo htmlspecialchars($row['sec_id']); ?>">Edit</a> |
                        <a href="delete_section.php?course_id=<?php echo htmlspecialchars($row['course_id']); ?>&sec_id=<?php echo htmlspecialchars($row['sec_id']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="9">No sections found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Section Form -->
<h3>Add New Section</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="sec_id" placeholder="Section ID" required>
    
    <!-- Course ID Dropdown -->
    <select name="course_id" required>
        <option value="">Select Course</option>
        <?php
        // Fetch courses for dropdown
        $course_query = "SELECT * FROM course";
        $course_result = mysqli_query($conn, $course_query);
        while ($course_row = mysqli_fetch_assoc($course_result)) {
            echo '<option value="' . htmlspecialchars($course_row['course_id']) . '">' . htmlspecialchars($course_row['title']) . '</option>';
        }
        ?>
    </select>
    
    <!-- Building Dropdown -->
    <select name="building" required>
        <option value="">Select Building</option>
        <?php
        // Fetch buildings for dropdown
        $building_query = "SELECT DISTINCT building FROM classroom";
        $building_result = mysqli_query($conn, $building_query);
        while ($building_row = mysqli_fetch_assoc($building_result)) {
            echo '<option value="' . htmlspecialchars($building_row['building']) . '">' . htmlspecialchars($building_row['building']) . '</option>';
        }
        ?>
    </select>
    
    <!-- Room Number Dropdown -->
    <select name="room_number" required>
        <option value="">Select Room Number</option>
        <?php
        // Fetch room numbers based on selected building
        $room_query = "SELECT room_number FROM classroom";
        $room_result = mysqli_query($conn, $room_query);
        while ($room_row = mysqli_fetch_assoc($room_result)) {
            echo '<option value="' . htmlspecialchars($room_row['room_number']) . '">' . htmlspecialchars($room_row['room_number']) . '</option>';
        }
        ?>
    </select>
    
    <!-- Time Slot ID Dropdown -->
    <select name="time_slot_id" required>
        <option value="">Select Time Slot</option>
        <?php
        // Fetch time slots for dropdown
        $time_slot_query = "SELECT * FROM time_slot"; // Make sure you have a time_slot table
        $time_slot_result = mysqli_query($conn, $time_slot_query);
        while ($time_slot_row = mysqli_fetch_assoc($time_slot_result)) {
            echo '<option value="' . htmlspecialchars($time_slot_row['time_slot_id']) . '">' . htmlspecialchars($time_slot_row['day'] . ' ' . $time_slot_row['start_time'] . ' - ' . $time_slot_row['end_time']) . '</option>';
        }
        ?>
    </select>

    <input type="text" name="semester" placeholder="Semester" required>
    <input type="number" name="year" placeholder="Year" min="1900" max="2100" required>
    <button type="submit">Add Section</button>
</form>

<?php
// Close database connection
mysqli_close($conn);
?>
