<?php
include 'includes/db.php';

// Handle create operation if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Time Slot
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $time_slot_id = mysqli_real_escape_string($conn, $_POST['time_slot_id']);
        $day = mysqli_real_escape_string($conn, $_POST['day']);
        $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
        $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);

        $query = "INSERT INTO time_slot (time_slot_id, day, start_time, end_time) 
                  VALUES ('$time_slot_id', '$day', '$start_time', '$end_time')";

        if (mysqli_query($conn, $query)) {
            echo "<p style='color:green;'>Time slot added successfully.</p>";
        } else {
            echo "<p style='color:red;'>Error adding time slot: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Fetch time slots from the database
$query = "SELECT * FROM time_slot";
$result = mysqli_query($conn, $query);
?>

<h2>Time Slots</h2>

<!-- Display Time Slots -->
<table border="1">
    <thead>
        <tr>
            <th>Time Slot ID</th>
            <th>Day</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['time_slot_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                    <td>
                        <a href="edit_time_slot.php?time_slot_id=<?php echo htmlspecialchars($row['time_slot_id']); ?>">Edit</a> |
                        <a href="delete_time_slot.php?time_slot_id=<?php echo htmlspecialchars($row['time_slot_id']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="5">No time slots found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Time Slot Form -->
<h3>Add New Time Slot</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="time_slot_id" placeholder="Time Slot ID" required>
    <input type="text" name="day" placeholder="Day" required>
    
    <label for="start_time">Start Time:</label>
    <input type="time" name="start_time" required>
    
    <label for="end_time">End Time:</label>
    <input type="time" name="end_time" required>
    
    <button type="submit">Add Time Slot</button>
</form>

<?php
// Close database connection
mysqli_close($conn);
?>
