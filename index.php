<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETS Admin Panel - Product Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styling */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Sidebar Styling */
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
            padding: 10px;
            text-decoration: none;
            text-align: center;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a.active, .sidebar a:hover {
            background-color: #0066cc;
        }

        /* Header Styling */
        .header {
            background-color: #004a9f;
            color: white;
            padding: 15px;
            margin-left: 200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .header h2 {
            margin: 0;
        }

        .header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 200px;
            padding: 30px;
            padding-top: 50px;
            flex: 1;
            box-sizing: border-box;
        }

        /* Adjust margin for the Add New Menu Item heading */
        .main-content h2 {
            margin-top: -10px;
            margin-bottom: 0px; /* Reduced spacing below the heading */
        }

        form input, form select, form textarea, form button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #004a9f;
            color: white;
        }

        /* Footer Styling */
        .footer {
            background-color: #004a9f;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 200px;
            width: calc(100% - 200px);
            box-sizing: border-box;
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
    <a href="index.php" class="active">Product Management</a>
    <a href="feedback_admin.php">View Feedback</a>
    <a href="settings.php">Settings</a>
</div>

<!-- Header -->
<div class="header">
    <h2>ETS Admin Panel - Product Management</h2>
    <div>
        <a href="homepage.php">Homepage</a>
        <a href="menu.php">Menu</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Add New Menu Item</h2>
    <form action="add_item.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Price (RM):</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="3"></textarea>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <label for="status">Stock Status:</label>
        <select id="status" name="status">
            <option value="In Stock">In Stock</option>
            <option value="Out of Stock">Out of Stock</option>
        </select>

        <button type="submit">Add Item</button>
    </form>

    <h2>Available Menu Items</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT productID, name, description, price, stockStatus FROM Product";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>RM " . number_format($row['price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stockStatus']) . "</td>";
                    echo "<td>
                            <a href='edit_item.php?id={$row['productID']}' style='color: blue;'>Edit</a> |
                            <a href='delete_item.php?id={$row['productID']}' style='color: red;' onclick=\"return confirm('Are you sure you want to delete this item?')\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No items available</td></tr>";
            }

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
