<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <!-- ETS Logo -->
    <div class="logo">
        <img src="ets_logo.png" alt="ETS Logo">
    </div>
    <a href="dashboard.php">Dashboard</a>
    <a href="orders.php">Orders</a>
    <a href="product_management.php" class="active">Product Management</a>
    <a href="feedback.php">View Feedback</a>
</div>


    <!-- Header -->
    <div class="header">
        <h2>ETS Admin Panel - Item Management</h2>
    </div>

    <!-- Content -->
    <div class="content">
        <h3>Add New Menu Item</h3>
        <form action="add_item.php" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" step="0.01" name="price" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category">
                        <option value="Drinks">Drinks</option>
                        <option value="Meals">Meals</option>
                        <option value="Snacks">Snacks</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="In Stock">In Stock</option>
                        <option value="Sold Out">Sold Out</option>
                    </select>
                </div>
                <div class="form-submit">
                    <button type="submit">Add Item</button>
                </div>
            </div>
        </form>

        <h3>Available Menu Items</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch menu items from the database
            $result = $conn->query("SELECT * FROM menu_items");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['category']}</td>
                    <td>RM {$row['price']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a class='button' href='edit_item.php?id={$row['id']}'>Edit</a>
                        <a class='button' href='delete_item.php?id={$row['id']}'>Delete</a>
                    </td>
                </tr>";
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
