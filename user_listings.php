<!DOCTYPE html>
<html>

<head>
    <title>Your Listings - House Rental Recommendation System</title>
    <style>
        table{
            border: 1px solid black;
            border-collapse: collapse;
        }
        td{
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
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

    // Retrieve flats posted by the currently logged-in user
    $username = $_SESSION["username"];
    $query = "SELECT * FROM flats WHERE posted_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<h1>Your Listings</h1>';
        echo '<table border="1">';
        echo '<tr>
                <td>Name</td>
                <td>Location</td>
                <td>Price(BDT/Month)</td>
                <td>Contact</td>
                <td>Image</td>
                <td colspan=2>Update</td>
                
              </tr>';

        while ($row = $result->fetch_assoc()) {
            // Format the price as an integer (removing decimal points)
            $formatted_price = number_format($row["price"], 0, '', '');
            echo '<tr>
                    <td>' . $row["name"] . '</td>
                    <td>' . $row["location"] . '</td>
                    <td>' . $formatted_price . ' TK</td>
                    <td>' . $row["contact_number"] . '</td>
                    <td><img src="' . $row["image_path"] . '" alt="' . $row["name"] . '" style="max-width: 150px; max-height: 150px;"></td>
                    <td><a href="edit_flat.php?id=' . $row["id"] . '">Edit</a></td>
                    <td><a href="delete_flat.php?id=' . $row["id"] . '" onclick="return confirm(\'Are you sure you want to delete this flat?\');">Delete</a></td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo "You have not posted any flats yet.";
    }

    $stmt->close();

    // Close the database connection
    $conn->close();
    ?>

    <br><br>
    <a href="homepage.php">Back to Homepage</a>
</body>

</html>