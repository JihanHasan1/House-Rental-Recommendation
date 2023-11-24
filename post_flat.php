<!DOCTYPE html>
<html>

<head>
    <title>Post</title>
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
            color: #FFFFFF;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            text-align: left;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            width: 92%;
            padding: 10px;
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

        .logo {
            position: absolute;
            top: -30px;
            left: 10px;
            width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <header>
        <a href="homepage.php"><img src="./images/logo.png" alt="Logo" class="logo"></a>
        <h1>Post a Flat</h1>
    </header>

    <?php
    session_start();

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

    function validatePhoneNumber($phone)
    {
        $pattern = '/^(\+88)?01[3-9]\d{8}$/';
        return preg_match($pattern, $phone);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = $_POST["name"];
        $location = $_POST["location"];
        $price = $_POST["price"];
        $contact = $_POST["contact"];
        $image_path = ""; // Initialize the image path
    
        // Validate the phone number
        if (!validatePhoneNumber($contact)) {
            // Display an error message if the phone number is invalid
            $_SESSION["error"] = "Invalid phone number format. Please use the format 123-456-7890.";
            header("Location: post_flat.php");
            exit();
        }

        // Handle image upload
        if (isset($_FILES["image"])) {
            $image_name = $_FILES["image"]["name"];
            $image_tmp_name = $_FILES["image"]["tmp_name"];
            $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

            // Generate a unique filename for the uploaded image
            $image_path = "uploads/" . uniqid() . "." . $image_extension;

            // Move the uploaded image to the desired directory
            move_uploaded_file($image_tmp_name, $image_path);
        }

        // Insert flat details into the database
        $username = $_SESSION["username"];
        $query = "INSERT INTO flats (name, location, price, contact_number, image_path, posted_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdsss", $name, $location, $price, $contact, $image_path, $username);

        if ($stmt->execute()) {
            // Display a success message using JavaScript alert
            echo '<script>alert("Flat posted successfully!");</script>';
        } else {
            // Display an error message using JavaScript alert
            echo '<script>alert("Error posting flat: ' . $stmt->error . '");</script>';
        }

        $stmt->close();
    }

    if (isset($_SESSION["error"])) {
        echo '<script>alert("' . $_SESSION["error"] . '");</script>';
        unset($_SESSION["error"]);
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <label for="name">Flat Name</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="location">Location</label>
        <input type="text" name="location" id="location" required><br><br>

        <label for="price">Price</label>
        <input type="text" name="price" id="price" required><br><br>

        <label for="contact">Contact</label>
        <input type="text" name="contact" id="contact" required><br><br>

        <label for="image">Upload Image</label>
        <input type="file" name="image" id="image"><br><br>

        <input type="submit" value="Post">
    </form>
</body>

</html>