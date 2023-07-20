<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carpooling";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the request data
$requestData = json_decode(file_get_contents('php://input'), true);

$pickupLocation = $requestData['pickup'];
$dropoffLocation = $requestData['dropoff'];

// Fetch the matching rides based on pickup and drop-off locations
$sql = "SELECT * FROM rides WHERE pickup_location LIKE ? AND dropoff_location LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $pickupLocation, $dropoffLocation);
$stmt->execute();
$result = $stmt->get_result();
$rides = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rides[] = $row;
    }
}

$stmt->close();

// Include append_results.php to append new results to $rides
include('append_results.php');
appendResultsUsingAlgorithm($rides, $pickupLocation, $dropoffLocation);

// Return the matching rides as JSON response
header('Content-Type: application/json');
echo json_encode($rides);

// Close the database connection
$conn->close();
?>
