<?php
include 'includes/db.php';

// Handle create operation if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Student
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $middle_initial = mysqli_real_escape_string($conn, $_POST['middle_initial']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $tot_cred = mysqli_real_escape_string($conn, $_POST['tot_cred']);
        $dept_name = mysqli_real_escape_string($conn, $_POST['dept_name']);

        // Insert student
        $query = "INSERT INTO student (id, first_name, middle_initial, last_name, dept_name, tot_cred) 
                  VALUES ('$student_id', '$first_name', '$middle_initial', '$last_name', '$dept_name', '$tot_cred')";

        if (mysqli_query($conn, $query)) {
            echo "<p style='color:green;'>Student added successfully.</p>";
        } else {
            echo "<p style='color:red;'>Error adding student: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Fetch all students along with their department names
$query = "SELECT s.*, d.dept_name FROM student s LEFT JOIN department d ON s.dept_name = d.dept_name"; 
$result = mysqli_query($conn, $query);
?>

<h2>Students</h2>

<!-- Display Students -->
<table border="1">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Middle Initial</th>
            <th>Last Name</th>
            <th>Total Credits</th> <!-- Total Credits Column -->
            <th>Department</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['middle_initial']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['tot_cred']); ?></td> <!-- Display Total Credits -->
                    <td><?php echo htmlspecialchars($row['dept_name']); ?></td> <!-- Display Department Name -->
                    <td>
                        <a href="edit_student.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a> |
                        <a href="delete_student.php?id=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="7">No students found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Student Form -->
<h3>Add New Student</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="student_id" placeholder="Student ID" required>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="middle_initial" placeholder="Middle Initial" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="number" name="tot_cred" placeholder="Total Credits" required>
    
    <!-- Department Dropdown -->
    <select name="dept_name" required>
        <option value="">Select Department</option>
        <?php
        // Fetch departments for dropdown
        $dept_query = "SELECT * FROM department";
        $dept_result = mysqli_query($conn, $dept_query);
        while ($dept_row = mysqli_fetch_assoc($dept_result)) {
            echo '<option value="' . htmlspecialchars($dept_row['dept_name']) . '">' . htmlspecialchars($dept_row['dept_name']) . '</option>';
        }
        ?>
    </select>
    
    <button type="submit">Add Student</button>
</form>

<?php
// Close database connection
mysqli_close($conn);
?>
