-- Table structure for table `users`
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT, -- User ID
  `name` VARCHAR(255) NOT NULL, -- User's name
  `email` VARCHAR(255) NOT NULL, -- User's email
  `password` VARCHAR(255) NOT NULL, -- User's password
  `default_dropoff` VARCHAR(255) NOT NULL, -- User's default drop-off location
  `user_type` TINYINT(1) NOT NULL -- User type (0 = regular user, 1 = admin)
);

-- Table structure for table `cars`
CREATE TABLE `cars` (
  `car_id` INT PRIMARY KEY AUTO_INCREMENT, -- Car ID
  `driver_id` INT NOT NULL, -- ID of the car's driver (linked to users table)
  `make` VARCHAR(255) NOT NULL, -- Car make
  `model` VARCHAR(255) NOT NULL, -- Car model
  `year` INT NOT NULL, -- Car manufacturing year
  `color` VARCHAR(255) NOT NULL, -- Car color
  `seating_capacity` INT NOT NULL, -- Car seating capacity
  FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) -- Foreign key referencing the driver in the users table
);

-- Table structure for table `rides`
CREATE TABLE `rides` (
  `ride_id` INT PRIMARY KEY AUTO_INCREMENT, -- Ride ID
  `driver_id` INT NOT NULL, -- ID of the ride's driver (linked to users table)
  `date` DATE DEFAULT CURRENT_DATE, -- Ride date (default to current date)
  `time` TIME DEFAULT CURRENT_TIME, -- Ride time (default to current time)
  `pickup_location` VARCHAR(255) NOT NULL, -- Pickup location
  `available_seats` INT NOT NULL, -- Number of available seats in the ride
  `status` VARCHAR(20) NOT NULL, -- Ride status (e.g., upcoming, in progress, completed, canceled)
  FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) -- Foreign key referencing the driver in the users table
);

-- Table structure for table `ride_passengers`
CREATE TABLE `ride_passengers` (
  `ride_id` INT NOT NULL, -- Ride ID (linked to rides table)
  `passenger_id` INT NOT NULL, -- ID of the ride's passenger (linked to users table)
  FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`), -- Foreign key referencing the ride in the rides table
  FOREIGN KEY (`passenger_id`) REFERENCES `users`(`id`) -- Foreign key referencing the passenger in the users table
);

-- Table structure for table `bookings`
CREATE TABLE `bookings` (
  `booking_id` INT PRIMARY KEY AUTO_INCREMENT, -- Booking ID
  `ride_id` INT NOT NULL, -- ID of the booked ride (linked to rides table)
  `passenger_id` INT NOT NULL, -- ID of the booking passenger (linked to users table)
  `booking_date` DATE NOT NULL, -- Booking date
  `booking_time` TIME NOT NULL, -- Booking time
  FOREIGN KEY (`ride_id`) REFERENCES `rides`(`ride_id`), -- Foreign key referencing the ride in the rides table
  FOREIGN KEY (`passenger_id`) REFERENCES `users`(`id`) -- Foreign key referencing the passenger in the users table
);
