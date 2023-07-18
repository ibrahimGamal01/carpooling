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

/**
 * Fetches the latitude and longitude coordinates for the given location using OpenStreetMap.
 *
 * @param string $location The location for which to fetch coordinates.
 * @return array|false The latitude and longitude coordinates as an array [latitude, longitude], or false if the coordinates cannot be fetched.
 */
function fetchCoordinates($location)
{
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($location);
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data)) {
        $latitude = $data[0]['lat'];
        $longitude = $data[0]['lon'];
        return [$latitude, $longitude];
    }

    return false;
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

// Append "Germany" to the input
$pickup .= " Germany";
$dropoff .= " Germany";

// Clean and format the input
$pickup = formatLocation($pickup);
$dropoff = formatLocation($dropoff);

// Fetch the latitude and longitude coordinates
$pickupCoordinates = fetchCoordinates($pickup);
$dropoffCoordinates = fetchCoordinates($dropoff);

// Get the driver's ID (You can modify this part to match your authentication system)
$driverId = 13; // Assuming the driver's ID is 13

// Prepare and execute the SQL query to create a new ride
$stmt = $conn->prepare("INSERT INTO rides (driver_id, pickup_location, dropoff_location, pickup_latitude, pickup_longitude, dropoff_latitude, dropoff_longitude, available_seats, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'upcoming')");
$stmt->bind_param("issssddi", $driverId, $pickup, $dropoff, $pickupCoordinates[0], $pickupCoordinates[1], $dropoffCoordinates[0], $dropoffCoordinates[1], $seats);

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

/**
 * Formats and cleans the given location by converting it to lowercase and removing special characters.
 *
 * @param string $location The location string to format and clean.
 * @return string The formatted and cleaned location string.
 */
function formatLocation($location)
{
    $location = strtolower($location);
    $location = preg_replace('/[^a-z0-9 ]/', '', $location);
    $location = trim($location);
    return $location;
}
?>
