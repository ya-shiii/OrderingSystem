<?php
// api/edit_teacher.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';

// Retrieve the teacher ID and other form data from the POST request
$teacher_id = $_POST['teacher_id'];
$username = $_POST['username'];
$password = $_POST['password'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];

// Create a response array to hold the update status
$response = array();

// Check if the new username already exists in the admin table
$checkAdminQuery = "SELECT * FROM admin WHERE username = '$username' AND teacher_id != '$teacher_id'";
$checkAdminResult = mysqli_query($conn, $checkAdminQuery);

// Check if the new username already exists in the teacher table
$checkTeacherQuery = "SELECT * FROM teacher WHERE username = '$username' AND teacher_id != '$teacher_id'";
$checkTeacherResult = mysqli_query($conn, $checkTeacherQuery);

if ($checkAdminResult && $checkTeacherResult && (mysqli_num_rows($checkAdminResult) > 0 || mysqli_num_rows($checkTeacherResult) > 0)) {
    // Username already exists, display an alert message
    echo '<script>alert("Username already exists");</script>';
    // Redirect to the previous page
    echo '<script>window.location.href = document.referrer;</script>';
    exit();
} else {
    // Update the teacher record in the database
    $sql = "UPDATE teacher SET username='$username', password='$password', first_name='$firstName', last_name='$lastName', age='$age', gender='$gender', email='$email', phone_number='$phoneNumber' WHERE teacher_id='$teacher_id'";

    if ($conn->query($sql) === TRUE) {
        // Record updated successfully
        echo '<script>alert("Record updated successfully");</script>';
        // Redirect to the previous page
        echo '<script>window.location.href = document.referrer;</script>';
        exit();
    } else {
        // Error updating record
        echo '<script>alert("Error updating record: ' . $conn->error . '");</script>';
        // Redirect to the previous page
        echo '<script>window.location.href = document.referrer;</script>';
        exit();
    }
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
