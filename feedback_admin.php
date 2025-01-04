<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETS Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #004a9f;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar img {
            width: 100px;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .sidebar a.active, .sidebar a:hover {
            background-color: #0066cc;
        }

        /* Header Styles */
        .header {
            background-color: #004a9f;
            color: white;
            padding: 15px;
            margin-left: 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        /* Content Styles */
        .content {
            margin-left: 200px;
            padding: 20px;
        }

        .content h3 {
            color: #004a9f;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #004a9f;
            color: white;
        }

        table td {
            color: #333;
        }

        /* Footer Styles */
        .footer {
            background-color: #004a9f;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-left: 200px;
        }

        .footer a {
            color: yellow;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="LOGO.png" alt="ETS Logo">
        </div>
        <a href="index.php">Dashboard</a>
        <a href="orders.php">Orders</a>
        <a href="index.php">Product Management</a>
        <a href="feedback.php" class="active">View Feedback</a>
        <a href="settings.php">Settings</a>
    </div>

    <!-- Header -->
    <div class="header">
        <h2>ETS Admin Panel - Feedback Management</h2>
        <div>
            <a href="homepage.php">Homepage</a>
            <a href="menu.php">Menu</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h3>Customer Feedback</h3>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Contact</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connect to database
                $conn = mysqli_connect("localhost", "root", "", "web-project");

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Fetch feedback data from the database
                $sql = "SELECT id, customer_name, contact, rating, feedback AS comments FROM feedback";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Check if there are rows in the result
                if (mysqli_num_rows($result) > 0) {
                    // Loop through the results and display them in a table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['contact']}</td>
                            <td>{$row['rating']} / 5</td>
                            <td>{$row['comments']}</td>
                        </tr>";
                    }
                } else {
                    // Display empty message
                    echo "<tr>
                        <td colspan='5' style='text-align: center;'>No feedback available</td>
                    </tr>";
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="footer">
        2018 All Rights Reserved KTM Berhad. KTM Berhad shall not be liable for any loss or damage caused by the usage of any 
        information obtained from this site. <br>
        © Copyright 2018 by <a href="#">KTM Berhad</a>
    </footer>
</body>
</html>
