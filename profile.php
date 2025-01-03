<?php
// Include database configuration
require 'db_config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Retrieve the logged-in user's ID
$userID = $_SESSION['userID'];

// Fetch user details from the database
try {
    $stmt = $pdo->prepare('SELECT name, email, phone FROM Customer WHERE customerID = :customerID');
    $stmt->bindParam(':customerID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Error fetching user details: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your existing CSS -->
    <style>
        .profile-container {
            background-color: white; /* White background for the profile section */
            padding: 30px; /* Increased padding for more space */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            max-width: 800px; /* Increased max width for the profile container */
            margin: 20px auto; /* Center the container */
            text-align: left; /* Align text to the left */
        }

        .profile-info {
            margin-bottom: 15px; /* Spacing between info items */
        }

        .profile-info label {
            font-weight: bold; /* Bold labels for clarity */
        }

        .edit-button {
            display: block; /* Make button block-level */
            width: 30%; /* Increased width for the button */
            padding: 12px; /* Slightly increased padding for comfort */
            background-color: #004a9f; /* Button color matching the theme */
            color: white; /* White text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor on hover */
            text-align: center; /* Center text in button */
            font-size: 18px;
            transition: background-color 0.3s; /* Smooth transition for hover effect */
            margin: 20px auto; /* Center the button horizontally */
        }

        .edit-button:hover {
            background-color: #003366; /* Darker shade on hover */
        }
    </style>

</head>
<body>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-icon-container')) {
                const dropdown = document.getElementById('dropdownMenu');
                dropdown.style.display = 'none';
            }
        }
    </script>

    <!-- Header -->
    <header class="header">
        <img src="LOGO.PNG" alt="ETS Logo">
        <nav>
            <a href="body.html">Homepage</a>
            <a href="#">Menu</a>
        </nav>
        <div class="header-icons">
            <img src="CART.PNG" alt="Cart Icon">

            <div class="profile-icon-container">
                <button onclick="toggleDropdown()" style="background: none; border: none; cursor: pointer;">
                    <img src="PROFILE.PNG" alt="Profile Icon">
                </button>
                <div id="dropdownMenu" class="dropdown-menu">
                    <a href="profile.php">Profile Page</a>
                    <a href="settings.html">Settings Page</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="profile-container">
            <h1>User Profile</h1><br>
            
            <div class="profile-info">
                <label>Name:</label> 
                <p><?php echo htmlspecialchars($user['name']); ?></p>
            </div>

            <div class="profile-info">
                <label>Phone Number:</label> 
                <p><?php echo htmlspecialchars($user['phone']); ?></p>
            </div>

            <div class="profile-info">
                <label>Email:</label> 
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <button onclick="location.href='edit_profile.php'" class="edit-button">Edit Profile</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        2018 All Rights Reserved KTM Berhad. KTM Berhad shall not be liable for any loss or damage caused by the usage of any 
        information obtained from this site. <br>
        © Copyright 2018 by <a href="#">KTM Berhad</a>
    </footer>
</body>
</html>