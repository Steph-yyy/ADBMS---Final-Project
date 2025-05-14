<?php
// delete_resident.php

// Connect to the database
$conn = new mysqli("localhost", "root", "", "resident_records");

// Check for DB connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is provided in the URL and ensure it is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $resident_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM approved_residents WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $resident_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect back to the approved residents page with success message
            header("Location: approved_residents.php?msg=deleted");
            exit();
        } else {
            // Redirect back with failure message if no rows were deleted
            header("Location: approved_residents.php?msg=failed");
            exit();
        }
    } else {
        // If SQL statement fails to prepare
        die("Delete query failed: " . $conn->error);
    }
} else {
    // Redirect back with invalid ID message if no valid 'id' is provided
    header("Location: approved_residents.php?msg=invalid_id");
    exit();
}
?>
