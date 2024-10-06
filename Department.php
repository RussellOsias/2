<?php
include 'includes/db.php';

// Handle create operation if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Department
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $dept_name = $_POST['dept_name'];

        // Insert into the database
        $sql = "INSERT INTO department (dept_name) VALUES ('$dept_name')";

        if ($conn->query($sql) === TRUE) {
            echo "New department added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch departments from the database
$sql = "SELECT * FROM department";
$result = $conn->query($sql);
?>

<h2>Departments</h2>

<!-- Display Departments -->
<table>
    <thead>
        <tr>
            <th>Department Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['dept_name']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['dept_name']; ?>">Edit</a> |
                        <a href="delete.php?id=<?php echo $row['dept_name']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="2">No departments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Department Form -->
<h3>Add New Department</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="dept_name" placeholder="Department Name" required>
    <button type="submit">Add Department</button>
</form>
