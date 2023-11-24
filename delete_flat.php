<?php
session_start();

// Check if the user is logged in (you can implement authentication here)

if (!isset($_SESSION["username"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Include database connection code here
$hostname = "localhost";
$username = "root";
$password = "";
$database = "rentdb";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the flat ID from the URL parameter
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $flat_id = $_GET["id"];

    // Check if the flat belongs to the currently logged-in user
    $username = $_SESSION["username"];
    $query = "SELECT * FROM flats WHERE id = ? AND posted_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $flat_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Delete the flat image from 'uploads' folder
        if (file_exists($row["image_path"])) {
            unlink($row["image_path"]);
        }

        // Delete the flat from the database
        $delete_query = "DELETE FROM flats WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $flat_id);

        if ($delete_stmt->execute()) {
            // Redirect back to the user's listings page
            header("Location: user_listings.php");
            exit();
        } else {
            echo "Error deleting flat: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        echo "You do not have permission to delete this flat.";
    }

    $stmt->close();
} else {
    echo "Invalid flat ID.";
}

// Close the database connection
$conn->close();
?>