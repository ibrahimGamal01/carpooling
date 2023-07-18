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
 * Fetches the matching rides based on pickup and drop-off locations.
 *
 * @param string $pickupLocation The pickup location.
 * @param string $dropoffLocation The drop-off location.
 * @return array The array of matching rides.
 */
function fetchRides($pickupLocation, $dropoffLocation)
{
    global $conn;

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

    return $rides;
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

// Retrieve the request data
$requestData = json_decode(file_get_contents('php://input'), true);

$pickupLocation = $requestData['pickup'];
$dropoffLocation = $requestData['dropoff'];

// Append "Germany" to the input
$pickupLocation .= " Germany";
$dropoffLocation .= " Germany";

// Clean and format the input
$pickupLocation = formatLocation($pickupLocation);
$dropoffLocation = formatLocation($dropoffLocation);

// Fetch the matching rides
$rides = fetchRides($pickupLocation, $dropoffLocation);

// Return the matching rides as JSON response
header('Content-Type: application/json');
echo json_encode($rides);

// Close the database connection
$conn->close();

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

<!-- 
CREATE TABLE `rides` (
  `ride_id` INT AUTO_INCREMENT PRIMARY KEY,
  `driver_id` INT NOT NULL,
  `pickup_location` VARCHAR(255) NOT NULL,
  `dropoff_location` VARCHAR(255) NOT NULL,
  `pickup_latitude` DECIMAL(10, 8),
  `pickup_longitude` DECIMAL(11, 8),
  `dropoff_latitude` DECIMAL(10, 8),
  `dropoff_longitude` DECIMAL(11, 8),
  `available_seats` INT NOT NULL,
  `status` ENUM('upcoming', 'in progress', 'completed', 'canceled') NOT NULL
); -->
