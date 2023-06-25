<?php

require_once 'db_connect.php';

// Retrieve the registration ID from the AJAX request
$registrationId = $_GET['registrationId'];

// Create a response array to hold the user information
$response = array();

// Retrieve the user information from the database based on the registration ID
$sql = "SELECT * FROM student WHERE student_id = $registrationId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User record found, retrieve the data
    $row = $result->fetch_assoc();
    
    // Assign the user data to the response array
    $response['userId'] = $row['student_id'];
    $response['password'] = $row['password'];
    $response['firstName'] = $row['first_name'];
    $response['lastName'] = $row['last_name'];
    $response['age'] = $row['age'];
    $response['address'] = $row['address'];
    $response['gender'] = $row['gender'];
    $response['email'] = $row['email'];
    $response['phoneNumber'] = $row['phone_number'];
    $response['status'] = $row['verify'];
} else {
    // No user record found
    $response['error'] = "User not found";
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
