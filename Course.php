<?php
include 'includes/db.php';

// Handle create, update, delete operations if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Course
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $course_id = $_POST['course_id'];
        $title = $_POST['title'];
        $dept_name = $_POST['dept_name'];
        $credits = $_POST['credits'];

        // Check if course_id already exists
        $check_query = "SELECT * FROM course WHERE course_id = '$course_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<p style='color:red;'>Error: Course with ID '$course_id' already exists.</p>";
        } else {
            $query = "INSERT INTO course (course_id, title, dept_name, credits) VALUES ('$course_id', '$title', '$dept_name', '$credits')";
            if (mysqli_query($conn, $query)) {
                echo "<p style='color:green;'>Course added successfully.</p>";
            } else {
                echo "<p style='color:red;'>Error adding course: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

// Fetch courses from the database
$query = "SELECT * FROM course";
$result = mysqli_query($conn, $query);
?>

<h2>Courses</h2>

<!-- Display Courses -->
<table border="1">
    <thead>
        <tr>
            <th>Course ID</th>
            <th>Title</th>
            <th>Department</th>
            <th>Credits</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['credits']); ?></td>
                    <td>
                        <a href="edit_course.php?course_id=<?php echo htmlspecialchars($row['course_id']); ?>">Edit</a> |
                        <a href="delete_course.php?course_id=<?php echo htmlspecialchars($row['course_id']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="5">No courses found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Course Form -->
<h3>Add New Course</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="course_id" placeholder="Course ID" required>
    <input type="text" name="title" placeholder="Title" required>
    
    <!-- Department Dropdown -->
    <select name="dept_name" required>
        <option value="">Select Department</option>
        <?php
        // Fetch departments for the dropdown
        $dept_sql = "SELECT dept_name FROM department";
        $dept_result = mysqli_query($conn, $dept_sql);
        if ($dept_result->num_rows > 0) :
            while ($dept_row = mysqli_fetch_assoc($dept_result)) : ?>
                <option value="<?php echo htmlspecialchars($dept_row['dept_name']); ?>"><?php echo htmlspecialchars($dept_row['dept_name']); ?></option>
            <?php endwhile; ?>
        <?php endif; ?>
    </select>

    <input type="number" name="credits" placeholder="Credits" min="0" required>

    <button type="submit">Add Course</button>
</form>
