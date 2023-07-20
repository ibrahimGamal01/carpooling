<?php
function appendResultsUsingAlgorithm(&$rides, $pickupLocation, $dropoffLocation)
{
    // Array of random pickup and dropoff location names
    $pickupNames = ['First Street', 'Central Avenue', 'Park Road', 'Sunset Boulevard', 'Main Square'];
    $dropoffNames = ['Lakeview Drive', 'Hillside Avenue', 'Garden Lane', 'Ocean Road', 'Mountain View'];

    // Randomly select pickup and dropoff location names from the arrays
    $randomPickupName = $pickupNames[array_rand($pickupNames)];
    $randomDropoffName = $dropoffNames[array_rand($dropoffNames)];

    // Generate a random number of available seats between 1 and 5
    $randomSeats = mt_rand(1, 5);

    // dummy test ride with random location names and seats
    $newRide = [
        'ride_id' => -1,
        'driver_id' => -1,
        'pickup_location' => $randomPickupName,
        'dropoff_location' => $randomDropoffName,
        'pickup_latitude' => 0.0,
        'pickup_longitude' => 0.0,
        'dropoff_latitude' => 0.0,
        'dropoff_longitude' => 0.0,
        'available_seats' => $randomSeats,
        'status' => 'upcoming',
    ];

    // Append the new ride to the existing rides array.
    $rides[] = $newRide;
}
