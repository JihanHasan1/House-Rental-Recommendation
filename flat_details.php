<?php
session_start();

// Include database connection code here
$hostname = "localhost";
$username = "root";
$password = "";
$database = "rentdb";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the flat ID from the query parameter
$flat_id = $_GET["id"];

// Retrieve flat details from the database
$query = "SELECT * FROM flats WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $flat_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Store the location of the last visited flat in a cookie
    setcookie("last_visited_location", $row["location"], time() + (30 * 24 * 60 * 60), "/"); // Expires in 30 days

    // Display flat details
    echo '<h1>Flat Details</h1>';
    echo '<img src="' . $row["image_path"] . '" alt="' . $row["name"] . '" style="max-width: 300px; max-height: 300px;">';
    echo '<p>Name: ' . $row["name"] . '</p>';
    echo '<p>Location: ' . $row["location"] . '</p>';
    echo '<p>Price: $' . $row["price"] . '</p>';
    echo '<p>Contact: ' . $row["contact_number"] . '</p>';

    // Add a link to go back to the modified homepage
    echo '<br><a href="homepage.php">Go Back to Homepage</a>';
} else {
    echo "Flat not found.";
}

// Close the database connection
$conn->close();
?>