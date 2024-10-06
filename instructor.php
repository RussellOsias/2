<?php
include 'includes/db.php';

// Handle create, update, delete operations if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Instructor
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $middle_initial = $_POST['middle_initial'];
        $last_name = $_POST['last_name'];
        $street_number = $_POST['street_number'];
        $street_name = $_POST['street_name'];
        $apt_number = $_POST['apt_number'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postal_code = $_POST['postal_code'];
        $date_of_birth = $_POST['date_of_birth'];
        $dept_name = $_POST['dept_name'];
        $salary = $_POST['salary'] ? $_POST['salary'] : 'NULL'; // Set to NULL if not provided

        // Check if ID already exists
        $check_sql = "SELECT * FROM instructor WHERE id = '$id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo "Error: Instructor with ID '$id' already exists.";
        } else {
            // Insert into the database
            $sql = "INSERT INTO instructor (id, first_name, middle_initial, last_name, street_number, street_name, apt_number, city, state, postal_code, date_of_birth, dept_name, salary) 
                    VALUES ('$id', '$first_name', '$middle_initial', '$last_name', '$street_number', '$street_name', '$apt_number', '$city', '$state', '$postal_code', '$date_of_birth', '$dept_name', $salary)";

            if ($conn->query($sql) === TRUE) {
                echo "New instructor added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Fetch instructors from the database
$sql = "SELECT * FROM instructor";
$result = $conn->query($sql);

// Fetch departments for the dropdown
$dept_sql = "SELECT dept_name FROM department";
$dept_result = $conn->query($dept_sql);
?>

<h2>Instructors</h2>

<!-- Display Instructors -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Middle Initial</th>
            <th>Last Name</th>
            <th>Street Number</th>
            <th>Street Name</th>
            <th>Apartment Number</th>
            <th>City</th>
            <th>State</th>
            <th>Postal Code</th>
            <th>Date of Birth</th>
            <th>Department</th>
            <th>Salary</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['middle_initial']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['street_number']; ?></td>
                    <td><?php echo $row['street_name']; ?></td>
                    <td><?php echo $row['apt_number']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><?php echo $row['state']; ?></td>
                    <td><?php echo $row['postal_code']; ?></td>
                    <td><?php echo $row['date_of_birth']; ?></td>
                    <td><?php echo $row['dept_name']; ?></td>
                    <td><?php echo $row['salary'] !== null ? '$' . number_format($row['salary']) : 'N/A'; ?></td> <!-- Display salary or N/A -->
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr>
                <td colspan="14">No instructors found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add Instructor Form -->
<h3>Add New Instructor</h3>
<form method="POST" action="">
    <input type="hidden" name="action" value="add">
    <input type="text" name="id" placeholder="ID" required>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="middle_initial" placeholder="Middle Initial">
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="street_number" placeholder="Street Number" required>
    <input type="text" name="street_name" placeholder="Street Name" required>
    <input type="text" name="apt_number" placeholder="Apartment Number">
    <input type="text" name="city" placeholder="City" required>
    <input type="text" name="state" placeholder="State" required>
    <input type="text" name="postal_code" placeholder="Postal Code" required>
    <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
    
    <!-- Department Dropdown -->
    <select name="dept_name" required>
        <option value="">Select Department</option>
        <?php if ($dept_result->num_rows > 0) : ?>
            <?php while ($dept_row = $dept_result->fetch_assoc()) : ?>
                <option value="<?php echo $dept_row['dept_name']; ?>"><?php echo $dept_row['dept_name']; ?></option>
            <?php endwhile; ?>
        <?php endif; ?>
    </select>

    <input type="number" name="salary" placeholder="Salary (Optional)">

    <button type="submit">Add Instructor</button>
</form>
