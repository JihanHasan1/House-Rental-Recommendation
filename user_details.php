<!DOCTYPE html>
<html>

<head>
    <title>User Details - House Rental Recommendation System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #007BFF;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo {
            position: absolute;
            top: 0px;
            left: 10px;
            width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <header>
        <a href="homepage.php"><img src="./images/logo.png" alt="Logo" class="logo"></a>
        <h1>User Details</h1>
    </header>
    <div class="container">
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

        $username = $_SESSION["username"];

        // Query the database to retrieve user details
        $query = "SELECT username, email FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            echo '<p><strong>Username:</strong> ' . $user["username"] . '</p>';
            echo '<p><strong>Email:</strong> ' . $user["email"] . '</p>';

            // Add a link to change password
            echo '<p><a href="change_password.php">Change Password</a></p>';
        } else {
            echo "User not found.";
        }

        $stmt->close();

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>

</html>