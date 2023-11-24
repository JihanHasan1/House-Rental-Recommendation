<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "rentdb";

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate user input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION["error"] = "All fields are required.";
        header("Location: signup.php");
        exit();
    }

    // Email validation using regex
    $email_pattern = '/^[a-z\d\._-]+@([a-z\d\.-]+\.)[a-z]{2,6}$/i';
    if (!preg_match($email_pattern, $email)) {
        $_SESSION["error"] = "Invalid email format.";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION["error"] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    // Check if the username or email already exists in the database
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $_SESSION["error"] = "Username or email already exists.";
        header("Location: signup.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($insert_query) === TRUE) {
        $_SESSION["success"] = "Sign up successful. You can now log in.";
    } else {
        $_SESSION["error"] = "Error: " . $conn->error;
    }

    $conn->close();

    // Redirect to the signup page after processing
    header("Location: signup.php");
    exit();
}
?>