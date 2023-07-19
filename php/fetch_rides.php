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

// Calculate the maximum allowable distance in kilometers (5 kilometers)
$maxDistance = 5;

// Fetch all rides from the database
$sql = "SELECT * FROM rides";
$result = $conn->query($sql);
$rides = [];

while ($row = $result->fetch_assoc()) {
    $pickupLat = $row['pickup_latitude'];
    $pickupLon = $row['pickup_longitude'];
    $dropoffLat = $row['dropoff_latitude'];
    $dropoffLon = $row['dropoff_longitude'];
    $rideId = $row['ride_id'];

    // Check for an exact match (same pickup and drop-off)
    if ($pickupLocation === $row['pickup_location'] && $dropoffLocation === $row['dropoff_location']) {
        $rides[] = $row;
    } else {
        // Calculate the distance between the pickup and drop-off locations of the ride and the requested locations
        function haversineDistance($lat1, $lon1, $lat2, $lon2) {
            $earthRadius = 6371; // Radius of the Earth in kilometers
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;
            return $distance;
        }

        $pickupDistance = haversineDistance($pickupLat, $pickupLon, $requestData['pickup_latitude'], $requestData['pickup_longitude']);
        $dropoffDistance = haversineDistance($dropoffLat, $dropoffLon, $requestData['dropoff_latitude'], $requestData['dropoff_longitude']);

        // Check for on the way match
        if ($pickupDistance <= $maxDistance && $dropoffDistance <= $maxDistance) {
            $rides[] = $row;
        } else {
            // Check for same drop-off match
            if ($dropoffLocation === $row['dropoff_location']) {
                $rides[] = $row;
            } else {
                // Check for same pickup match
                if ($pickupLocation === $row['pickup_location']) {
                    $rides[] = $row;
                } else {
                    // Check for general match (within a distance threshold)
                    if ($pickupDistance <= $maxDistance || $dropoffDistance <= $maxDistance) {
                        $rides[] = $row;
                    }
                }
            }
        }
    }
}

// Return the matching rides as JSON response
header('Content-Type: application/json');
echo json_encode($rides);

// Close the database connection
$conn->close();
?>
