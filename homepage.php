<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
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

        .navbar {
            overflow: hidden;
        }

        .navbar-center {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            color: white;
            font-size: 30px;
        }

        .navbar a {
            float: right;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            color: white;
            text-decoration: underline;
            text-decoration-color: red;
        }

        /* Filter Form Styles */
        .filter-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;

        }

        .filter-form {
            justify-content: center;
            align-items: right;
        }

        .filter-form label {
            width: 100%;
            margin-bottom: 10px;
            color: #333;
            font-weight: bold;
        }

        .filter-form input[type="text"],
        .filter-form select,
        .filter-form input[type="submit"] {
            width: 10%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .filter-form input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

        .filter-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Card Styles */
        .flat-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            width: 200px;
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
        }

        .flat-card:hover {
            transform: scale(1.05);
        }

        .flat-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            /* Crop the image to cover the container */
        }

        .logo {
            position: absolute;
            top: -40px;
            left: 10px;
            width: 150px;
            height: 150px;
        }

        /* Table Styles (for small screens) */
        @media screen and (max-width: 600px) {
            .flat-card {
                display: block;
                width: 90%;
                margin: 10px auto;
            }
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

    // Retrieve the last visited location from the cookie
    $last_visited_location = isset($_COOKIE['last_visited_location']) ? $_COOKIE['last_visited_location'] : '';

    // Check if the user submitted the filter form and store filter parameters in sessions
    if (isset($_GET["apply_filters"])) {
        $_SESSION["filter_search"] = $_GET["search"];
        $_SESSION["filter_location"] = $_GET["location"];
        $_SESSION["filter_budget"] = $_GET["budget"];
    }

    // Retrieve filter parameters from sessions
    $filter_search = isset($_SESSION['filter_search']) ? $_SESSION['filter_search'] : '';
    $filter_location = isset($_SESSION['filter_location']) ? $_SESSION['filter_location'] : '';
    $filter_budget = isset($_SESSION['filter_budget']) ? $_SESSION['filter_budget'] : '';

    // Display navigation bar
    echo '<div class="navbar">
            <a href="homepage.php"><img src="./images/logo.png" alt="Logo" class="logo"></a>
            <span class="navbar-center welcome-message">Welcome, ' . $_SESSION["username"] . ' !</span>
            <a href="logout.php">Logout</a>
            <a href="user_details.php">Profile</a>
            <a href="user_listings.php">My Listings</a>
            <a href="post_flat.php">Add Post</a>
        </div>';


    // Display filter form
    echo '<div class="filter-container">
            <h2>Search and Filters</h2>
            <form class="filter-form" method="get" action="">
                <label for="search">Search by Name:</label>
                <input type="text" name="search" id="search" value="' . $filter_search . '">';
    // Fetch distinct locations from the database for filtering
    $locationQuery = "SELECT DISTINCT location FROM flats";
    $locationResult = $conn->query($locationQuery);
    echo '<label for="location">Filter by Location:</label>
                        <select name="location" id="location">
                            <option value="">All Locations</option>';
    if ($locationResult->num_rows > 0) {
        while ($row = $locationResult->fetch_assoc()) {
            $location = $row["location"];
            $selected = ($filter_location === $location) ? "selected" : "";
            echo '<option value="' . $location . '" ' . $selected . '>' . $location . '</option>';
        }
    }
    echo '</select>
                <label for="budget">Filter by Budget:</label>
                <input type="text" name="budget" id="budget" value="' . $filter_budget . '">
                <input type="submit" name="apply_filters" value="Apply Filters">
            </form>
        </div>';
    // Construct the SQL query based on filters
    $query = "SELECT * FROM flats WHERE 1";
    if (!empty($filter_search)) {
        // Modify the SQL query to include the search filter
        $query .= " AND name LIKE '%$filter_search%'";
    }
    if (!empty($filter_location)) {
        // Modify the SQL query to include the location filter
        $query .= " AND location = '$filter_location'";
    }
    if (!empty($filter_budget)) {
        // Modify the SQL query to include the budget filter
        $query .= " AND price <= '$filter_budget'";
    }
    if (!empty($last_visited_location)) {
        $query .= " ORDER BY CASE WHEN location = '$last_visited_location' THEN 1 ELSE 2 END";
    } else {
        $query .= " ORDER BY id";
    }
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Display the list of available flats in cards
        while ($row = $result->fetch_assoc()) {
            // Format the price as an integer (removing decimal points)
            $formatted_price = number_format($row["price"], 0, '', '');
            echo '<div class="flat-card">
                        <a href="flat_details.php?id=' . $row["id"] . '">
                            <img src="' . $row["image_path"] . '" alt="' . $row["name"] . '">
                        </a>
                        <p><strong>Name:</strong> ' . $row["name"] . '</p>
                        <p><strong>Location:</strong> ' . $row["location"] . '</p>
                        <p><strong>Price:</strong> BDT. ' . $formatted_price . '/Month</p>
                    </div>';
        }
    } else {
        echo '<p style="color:white;">No Flat Found !</p>';
    }

    $conn->close();
    ?>
</body>

</html>