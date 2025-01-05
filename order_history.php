<?php
// Start session
session_start();

// Database connection
$servername = "localhost"; // replace with your server name
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "web-project"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming a user is logged in and their customerID is stored in the session
$customerID = $_SESSION['customerID'] ?? null;

// If no customer is logged in, redirect to the homepage
if (!$customerID) {
    header("Location: index.php");
    exit;
}

// Fetch order history for the logged-in user
$sql = "SELECT * FROM Orders WHERE customerID = $customerID ORDER BY orderDate DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
        }

        .header {
            background-color: #004a9f;
            color: white;
            padding: 10px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif; 
        }

        .header img {
            height: 60px;
            width: auto;
        }

        .header nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .header nav a:hover {
            color: #ffd700;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icons img {
            width: 36px;
            height: 36px;
            cursor: pointer;
        }

        .content h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .order-table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-table th, .order-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .order-table th {
            background-color: #004a9f;
            color: white;
        }

        .no-orders {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            background-color: #004a9f;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            margin-top: auto;
        }

        .footer a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
            transition: text-decoration 0.3s;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Profile Container for Dropdown */
        .profile-icon-container {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            display: none; 
            position: absolute;
            top: 50px;
            right: 0; 
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-width: 150px;
            z-index: 1000; 
        }

        /* Dropdown Menu Links */
        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-size: 16px;
        }

        .dropdown-menu a:hover {
            background-color: #f4f4f4; 
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: center;
            }

            .header nav {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        // Close the dropdown when clicking outside of it
        window.onclick = function(event) {
            if (!event.target.closest('.profile-icon-container')) {
                const dropdown = document.getElementById('dropdownMenu');
                dropdown.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <img src="LOGO.PNG" alt="ETS Logo">
        <nav>
            <a href="#">Homepage</a>
            <a href="#">Menu</a>
        </nav>
        <div class="header-icons">
            <img src="CART.PNG" alt="Cart Icon">
            <div class="profile-icon-container">
                <button onclick="toggleDropdown()" style="background: none; border: none; cursor: pointer;">
                    <img src="PROFILE.PNG" alt="Profile Icon">
                </button>
                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="dropdown-menu">
                    <a href="profile.html">Profile Page</a>
                    <a href="order_history.php">Order History</a>
                    <a href="feedback_user.php">Feedback Page</a>
                    <a href="settings.html">Settings Page</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <h1>Order History</h1>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Food</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['orderID'] . "</td>";
                        echo "<td>Food Name</td>"; // Replace with actual food name
                        echo "<td>" . $row['totalAmount'] . "</td>";
                        echo "<td>1</td>"; // Example quantity
                        echo "<td>" . $row['totalAmount'] . "</td>"; // Example total
                        echo "<td>" . $row['orderDate'] . "</td>";
                        echo "<td>" . $row['orderStatus'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='no-orders'>You have not placed any orders yet!!!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        2018 All Rights Reserved KTM Berhad. KTM Berhad shall not be liable for any loss or damage caused by the usage of any 
        information obtained from this site. <br>
        &copy; Copyright 2018 by <a href="#">KTM Berhad</a>
    </footer>

    <?php
    // Close the connection
    $conn->close();
    ?>
</body>
</html>
