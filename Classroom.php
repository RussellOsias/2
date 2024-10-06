<?php
include 'includes/db.php';

// Handle create, update, delete operations if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Classroom
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $building = $_POST['building'];
        $room_number = $_POST['room_number'];
        $capacity = $_POST['capacity'];

        // Check if the classroom already exists
        $check_query = "SELECT * FROM classroom WHERE building = '$building' AND room_number = '$room_number'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<p style='color:red;'>Error: Classroom '$building - $room_number' already exists.</p>";
        } else {
            $query = "INSERT INTO classroom (building, room_number, capacity) VALUES ('$building', '$room_number', '$capacity')";
            if (mysqli_query($conn, $query)) {
                echo "<p style='color:green;'>Classroom added successfully.</p>";
            } else {
                echo "<p style='color:red;'>Error adding classroom: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

// Fetch classrooms from the database
$query = "SELECT * FROM classroom";
$result = mysqli_query($conn, $query);
?>

<h2>Classrooms</h2>

<!-- Display Classrooms -->
<table border="1">
    <thead>
        <tr>
            <th>Building</th>
            <th>Room Number</th>
            <th>Capacity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['building']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                    <td>
                        <a href="edit_classroom.php?building=<?php echo htmlspecialchars($row['building']); ?>&room_number=<?php echo htmlspecialchars($row['room_number']); ?>">Edit</a> |
                        <a href="delete_classroom.php?building=<?php echo htmlspecialchars($row['building']); ?>&room_number=<?php echo htmlspecialchars($row['room_number']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">No classrooms found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Classroom Form -->
<h3>Add New Classroom</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="building" placeholder="Building" required>
    <input type="text" name="room_number" placeholder="Room Number" required>
    <input type="number" name="capacity" placeholder="Capacity" min="0" required>
    <button type="submit">Add Classroom</button>
</form>
