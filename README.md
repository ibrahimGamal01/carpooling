# Carpooling

## ER Diagram

![ERD](Documentation/ER_Diagram.png)

## Activity Diagram

![Activity Diagram](Documentation/Activity_Diagram.svg)

link: [Activity Diagram Lucidchart](https://lucid.app/documents/embedded/612ab06c-b2ce-42f6-a908-c0d885258926#)

<!-- link: [Class Diagram Lucidchart](https://lucid.app/documents/embedded/685b0fd3-012c-4ea6-9e92-f7fae120cae1) -->


## File Structure

The file structure of this project is organized as follows:

- `passenger.html`: HTML file for the passenger interface.
- `driver.html`: HTML file for the driver interface.
- `styles.css`: CSS file for the basic interfcaes.
- `database.sql`: SQL script for creating the database schema.
- `php/`: Directory containing PHP scripts used for server-side processing.
    - `create_ride.php`: PHP script for creating a new ride.
    - `join_ride.php`: PHP script for joining an existing ride.
    - `database.php`: PHP script for connecting to the database.
- `src/`: Directory containing static assets such as images and icons.
    - `logo.png`: Image file for the project logo.
    - `favicon/`: Directory containing various sizes of the favicon icon.
        - `android-chrome-192x192.png`: Icon file for Android devices.
        - `android-chrome-512x512.png`: Icon file for Android devices.
        - `apple-touch-icon.png`: Icon file for iOS devices.
        - `favicon.ico`: Icon file for web browsers.
        - `favicon-16x16.png`: Icon file for web browsers.
        - `favicon-32x32.png`: Icon file for web browsers.