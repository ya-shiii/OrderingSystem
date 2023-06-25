<?php
// make_announcement.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the announcement text and teacher ID from the form
    $announcementText = $_POST["announcementText"];
    $teacher_id = $_POST["teacher_id"];

    // Perform any necessary validation or processing with the announcement data

    // Example: Add the announcement to the database using mysqli

    // Replace the database connection details with your own
    $servername = "localhost";
    $username = "u463973717_srannounce";
    $password = "March022000";
    $dbname = "u463973717_srannouncement";

    // Create a new mysqli connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO announcement (teacher_id, message) VALUES (?, ?)");

    // Bind the values to the parameters
    $stmt->bind_param("is", $teacher_id, $announcementText);

    // Execute the statement
    if ($stmt->execute()) {
        // Announcement added successfully
        echo "Announcement added successfully!";
    } else {
        // Error adding announcement
        echo "Error adding announcement: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the page or perform any other actions
    echo "<script>alert('Announcement Posted'); window.location.href='../index.php';</script>";

    
    exit();
}
?>
