<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection code here
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "rentdb";

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]) ? true : false;

    // Check the user's credentials in the database
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $user["password"])) {
            // Password is correct, set session variables
            $_SESSION["username"] = $username;

            // Set a cookie if "Remember Me" is checked
            if ($remember_me) {
                $cookie_name = "user";
                $cookie_value = $username;
                setcookie($cookie_name, $cookie_value, time() + (30 * 24 * 60 * 60), "/"); // Cookie for 30 days
            }

            header("Location: homepage.php");
            exit();
        }
    }

    // If we reach this point, there was an error
    $_SESSION["error"] = "Incorrect username or password.";
    header("Location: login.php");
    exit();
}
?>
