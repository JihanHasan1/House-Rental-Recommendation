<!DOCTYPE html>
<html>

<head>
    <title>Edit Flat - House Rental Recommendation System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./images/Home.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #fff;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            /* Add a semi-transparent white background */
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            text-align: left;
            color: #333;
            font-weight: bold;
        }

        input[type="text"] {
            width: 92%;
            padding: 10px;
            margin-bottom: 2px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        a {
            color: #FFFFFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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

    // Retrieve the flat ID from the URL parameter
    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $flat_id = $_GET["id"];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $name = $_POST["name"];
            $location = $_POST["location"];
            $price = $_POST["price"];
            $contact = $_POST["contact"];

            // Update flat details in the database
            $query = "UPDATE flats SET name = ?, location = ?, price = ?, contact_number = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssdsi", $name, $location, $price, $contact, $flat_id);

            if ($stmt->execute()) {
                // Display a success message using JavaScript alert
                echo '<script>alert("Flat details updated successfully!");</script>';
            } else {
                // Display an error message using JavaScript alert
                echo '<script>alert("Error updating flat details: ' . $stmt->error . '");</script>';
            }

            $stmt->close();
        }

        // Query the database to retrieve flat details
        $query = "SELECT * FROM flats WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $flat_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Display a form pre-filled with the flat's current details
            echo '<h1>Edit Flat</h1>';
            echo '<form method="post">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="' . $row["name"] . '" required><br><br>
                    
                    <label for="location">Location:</label>
                    <input type="text" name="location" id="location" value="' . $row["location"] . '" required><br><br>
                    
                    <label for="price">Price:</label>
                    <input type="text" name="price" id="price" value="' . $row["price"] . '" required><br><br>
                    
                    <label for="contact">Contact:</label>
                    <input type="text" name="contact" id="contact" value="' . $row["contact_number"] . '" required><br><br>
                    
                    <input type="submit" value="Update">
                </form>';
        } else {
            echo "Flat not found.";
        }

        $stmt->close();
    } else {
        echo "Invalid flat ID.";
    }

    // Close the database connection
    $conn->close();
    ?>

    <br><br>
    <a href="user_listings.php">Back to Your Listings</a>
</body>

</html>