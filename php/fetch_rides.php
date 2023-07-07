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

// Retrieve pickup and drop-off locations from the request
$requestData = json_decode(file_get_contents("php://input"), true);
$pickup = $requestData['pickup'];
$dropoff = $requestData['dropoff'];

// Prepare and execute the SQL query to fetch matching rides
$stmt = $conn->prepare("SELECT * FROM rides WHERE pickup_location = ? AND status = 'upcoming'");
$stmt->bind_param("s", $pickup);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the rides and store them in an array
$rides = array();
while ($row = $result->fetch_assoc()) {
    $rides[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Return the rides as JSON response
echo json_encode($rides);
?>
