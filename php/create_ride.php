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
$requestData = $_POST;
$pickup = isset($requestData['pickup']) ? $requestData['pickup'] : '';
$dropoff = isset($requestData['dropoff']) ? $requestData['dropoff'] : '';
$seats = isset($requestData['seats']) ? $requestData['seats'] : '';

// Validate the required fields
if (empty($pickup) || empty($dropoff) || empty($seats)) {
  die("Error: Required fields are missing.");
}

// Get the driver's ID (You can modify this part to match your authentication system)
$driverId = 13; // Assuming the driver's ID is 1

// Prepare and execute the SQL query to create a new ride
$stmt = $conn->prepare("INSERT INTO rides (driver_id, pickup_location, dropoff_location, available_seats, status) VALUES (?, ?, ?, ?, 'upcoming')");
$stmt->bind_param("isss", $driverId, $pickup, $dropoff, $seats);

if ($stmt->execute()) {
  // Get the newly created ride's ID
  $rideId = $stmt->insert_id;

  // Close the database connection
  $stmt->close();
  $conn->close();

  // Return the ride details as JSON response
  $response = array(
    'rideId' => $rideId,
    'driver' => 'John Doe', // Replace with the actual driver's name
    'pickup' => $pickup,
    'dropoff' => $dropoff,
    'seats' => $seats
  );

  echo json_encode($response);
} else {
  echo "Error: " . $stmt->error;
}


?>