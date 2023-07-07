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

// Retrieve the ride ID from the request
$requestData = json_decode(file_get_contents("php://input"), true);
$rideId = $requestData['rideId'];

// Prepare and execute the SQL query to fetch the passenger list for the ride
$stmt = $conn->prepare("SELECT users.name FROM ride_passengers INNER JOIN users ON ride_passengers.passenger_id = users.id WHERE ride_id = ?");
$stmt->bind_param("i", $rideId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the passengers and store them in an array
$passengers = array();
while ($row = $result->fetch_assoc()) {
  $passengers[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Return the passengers as JSON response
echo json_encode($passengers);
?>
