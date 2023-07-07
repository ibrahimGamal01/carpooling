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

// Retrieve ride details from the request
$requestData = json_decode(file_get_contents("php://input"), true);
$pickup = $_POST['pickup'];
$dropoff = $_POST['dropoff'];
$seats = $_POST['seats'];

// Get the driver's ID (You can modify this part to match your authentication system)
$driverId = 1; // Assuming the driver's ID is 1

// Prepare and execute the SQL query to create a new ride
$stmt = $conn->prepare("INSERT INTO rides (driver_id, pickup_location, dropoff_location, available_seats, status) VALUES (?, ?, ?, ?, 'upcoming')");
$stmt->bind_param("isssi", $driverId, $pickup, $pickup, $seats);
$stmt->execute();

// Get the newly created ride's ID
$rideId = $stmt->insert_id;

// Close the database connection
$stmt->close();
$conn->close();

// Return the ride details as JSON response
$response = array(
  'rideId' => $rideId,
  'driver' => 'Driver Name', // Replace with the actual driver's name
  'pickup' => $pickup,
  'dropoff' => $dropoff,
  'seats' => $seats
);

echo json_encode($response);
?>
