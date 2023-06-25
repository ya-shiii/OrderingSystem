<?php
// api/edit_student.php

require_once 'db_connect.php';

// Retrieve the student ID and other form data from the POST request
$studentId = $_POST['studId'];
$password = $_POST['password'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$age = $_POST['age'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];

// Create a response array to hold the update status
$response = array();

// Update the user record in the database
$sql = "UPDATE student SET password='$password', first_name='$firstName', last_name='$lastName', age='$age', address='$address', gender='$gender', email='$email', phone_number='$phoneNumber' WHERE student_id='$studentId'";

if ($conn->query($sql) === TRUE) {
    // Record updated successfully
    echo '<script>alert("Record updated successfully");</script>';
    // Redirect to the previous page
    echo '<script>window.location.href = document.referrer;</script>';
    exit();
} else {
    // Error updating record
    // Display an alert message
    echo '<script>alert("Error updating record: ' . $conn->error . '");</script>';
    // Redirect to the previous page
    echo '<script>window.history.go(-1);</script>';
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
