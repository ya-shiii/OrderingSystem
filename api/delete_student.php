<?php
// delete_student.php

require_once "db_connect.php";

// Check if the registration_id is provided
if (isset($_POST['registration_id'])) {
    // Retrieve the registration ID from the POST request
    $registrationId = $_POST['registration_id'];

    // Perform any necessary validation or processing with the registration ID

    // Example: Delete the student from the database using mysqli

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM student WHERE student_id = ?");

    // Bind the value to the parameter
    $stmt->bind_param("s", $registrationId);

    // Execute the statement
    if ($stmt->execute()) {
        // Student deleted successfully
        echo '<script>alert("Record deleted successfully");</script>';
        // Redirect to the previous page
        echo '<script>window.location.href = document.referrer;</script>';
        exit();
    } else {
        // Error deleting student
        echo '<script>alert("Error updating record: ' . $conn->error . '");</script>';
        // Redirect to the previous page
        echo '<script>window.location.href = document.referrer;</script>';
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    // No registration ID provided
    echo "Registration ID not found.";
}

// Close the database connection
$conn->close();
?>
