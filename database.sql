-- Table structure for table `users`
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT, -- User ID
  `name` VARCHAR(255) NOT NULL, -- User's name
  `email` VARCHAR(255) NOT NULL, -- User's email
  `password` VARCHAR(255) NOT NULL, -- User's password
  `defaultDropOff` VARCHAR(255) NOT NULL, -- User's default drop-off location
  `userType` TINYINT(1) NOT NULL -- User type (e.g., 0 = regular user, 1 = admin)
);

-- Table structure for table `cars`
CREATE TABLE `cars` (
  `carId` INT PRIMARY KEY AUTO_INCREMENT, -- Car ID
  `driverId` INT NOT NULL, -- ID of the car's driver (linked to users table)
  `make` VARCHAR(255) NOT NULL, -- Car make
  `model` VARCHAR(255) NOT NULL, -- Car model
  `year` INT NOT NULL, -- Car manufacturing year
  `color` VARCHAR(255) NOT NULL, -- Car color
  `seatingCapacity` INT NOT NULL, -- Car seating capacity
  FOREIGN KEY (`driverId`) REFERENCES `users`(`id`) -- Foreign key referencing the driver in the users table
);

-- Table structure for table `rides`
CREATE TABLE `rides` (
  `rideId` INT PRIMARY KEY AUTO_INCREMENT, -- Ride ID
  `driverId` INT NOT NULL, -- ID of the ride's driver (linked to users table)
  `date` DATE DEFAULT CURRENT_DATE, -- Ride date (default to current date)
  `time` TIME DEFAULT CURRENT_TIME, -- Ride time (default to current time)
  `pickupLocation` VARCHAR(255) NOT NULL, -- Pickup location
  `availableSeats` INT NOT NULL, -- Number of available seats in the ride
  `status` VARCHAR(20) NOT NULL, -- Ride status (e.g., upcoming, in progress, completed, canceled)
  FOREIGN KEY (`driverId`) REFERENCES `users`(`id`) -- Foreign key referencing the driver in the users table
);

-- Table structure for table `ride_passengers`
CREATE TABLE `ride_passengers` (
  `rideId` INT NOT NULL, -- Ride ID (linked to rides table)
  `passengerId` INT NOT NULL, -- ID of the ride's passenger (linked to users table)
  FOREIGN KEY (`rideId`) REFERENCES `rides`(`rideId`), -- Foreign key referencing the ride in the rides table
  FOREIGN KEY (`passengerId`) REFERENCES `users`(`id`) -- Foreign key referencing the passenger in the users table
);

-- Table structure for table `bookings`
CREATE TABLE `bookings` (
  `bookingId` INT PRIMARY KEY AUTO_INCREMENT, -- Booking ID
  `rideId` INT NOT NULL, -- ID of the booked ride (linked to rides table)
  `passengerId` INT NOT NULL, -- ID of the booking passenger (linked to users table)
  `bookingDate` DATE NOT NULL, -- Booking date
  `bookingTime` TIME NOT NULL, -- Booking time
  FOREIGN KEY (`rideId`) REFERENCES `rides`(`rideId`), -- Foreign key referencing the ride in the rides table
  FOREIGN KEY (`passengerId`) REFERENCES `users`(`id`) -- Foreign key referencing the passenger in the users table
);
