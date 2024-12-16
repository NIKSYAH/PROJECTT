<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- ETS Logo -->
        <div class="logo">
            <img src="ets_logo.png" alt="ETS Logo">
        </div>
        <a href="index.php">Dashboard</a>
        <a href="orders.php">Orders</a>
        <a href="index.php">Product Management</a>
        <a href="feedback.php" class="active">View Feedback</a>
    </div>

    <!-- Header -->
    <div class="header">
        <h2>ETS Admin Panel - Feedback Management</h2>
    </div>

    <!-- Content -->
    <div class="content">
        <h3>Customer Feedback</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Rating</th>
                <th>Comments</th>
                <th>Image</th>
            </tr>
            <?php
            // Fetch feedback data from the database
            $result = $conn->query("SELECT * FROM feedback");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['rating']} / 5</td>
                    <td>{$row['comments']}</td>
                    <td>";
                if (!empty($row['image'])) {
                    echo "<img src='{$row['image']}' alt='Feedback Image' style='width: 50px; height: auto;'>";
                } else {
                    echo "No Image";
                }
                echo "</td></tr>";
            }
            ?>
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
