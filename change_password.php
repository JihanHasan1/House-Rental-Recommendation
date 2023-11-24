<!DOCTYPE html>
<html>

<head>
    <title>Change Password - House Rental Recommendation System</title>
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

        input[type="password"] {
            width: 92%;
            padding: 10px;
            margin-bottom: 15px;
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

    $username = $_SESSION["username"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        // Query the database to retrieve the current hashed password
        $query = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password = $user["password"];

            // Verify the current password
            if (password_verify($current_password, $hashed_password)) {
                // Validate the new password
                if ($new_password === $confirm_password) {
                    // Hash the new password
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update the user's password in the database
                    $update_query = "UPDATE users SET password = ? WHERE username = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("ss", $new_hashed_password, $username);

                    if ($update_stmt->execute()) {
                        // Password updated successfully
                        echo '<script>alert("Password changed successfully!");</script>';
                    } else {
                        echo '<script>alert("Error changing password: ' . $update_stmt->error . '");</script>';
                    }

                    $update_stmt->close();
                } else {
                    echo '<script>alert("New passwords do not match.");</script>';
                }
            } else {
                echo '<script>alert("Current password is incorrect.");</script>';
            }
        } else {
            echo '<script>alert("User not found.");</script>';
        }

        $stmt->close();
    }
    ?>

    <h1>Change Password</h1>
    <form method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>

        <input type="submit" value="Change Password">
    </form>

    <br><br>
    <a href="user_details.php">Back to User Details</a>
</body>

</html>