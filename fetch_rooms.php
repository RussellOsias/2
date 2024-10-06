<?php
include 'includes/db.php';

// Check if building ID is provided
if (isset($_GET['building_id'])) {
    $building_id = mysqli_real_escape_string($conn, $_GET['building_id']);

    // Query to fetch rooms based on the building ID
    $query = "SELECT room_number FROM classroom WHERE building_id = '$building_id'"; // Make sure this matches your classroom table's structure
    $result = mysqli_query($conn, $query);

    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row; // Collect room numbers
    }

    // Return room numbers as JSON
    echo json_encode($rooms);
}

// Close database connection
mysqli_close($conn);
?>
