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

// Initialize variables
$error = '';
$success = '';
$themeMode = 'light'; // Default theme
$notifications = [];

// Fetch current user settings
try {
    $stmt = $pdo->prepare('SELECT themeMode, notifications FROM Customer WHERE customerID = :customerID');
    $stmt->bindParam(':customerID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $themeMode = $result['themeMode'] ?? 'light';
        $notifications = explode(',', $result['notifications']) ?? [];
    }
} catch (PDOException $e) {
    die("Error fetching user settings: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle password change
    if (isset($_POST['currentPassword'], $_POST['newPassword'], $_POST['confirmPassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        try {
            // Fetch current password from the database
            $stmt = $pdo->prepare('SELECT password FROM Customer WHERE customerID = :customerID');
            $stmt->bindParam(':customerID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify current password
            if (password_verify($currentPassword, $user['password'])) {
                if ($newPassword === $confirmPassword) {
                    // Hash and update the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare('UPDATE Customer SET password = :password WHERE customerID = :customerID');
                    $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                    $updateStmt->bindParam(':customerID', $userID, PDO::PARAM_INT);

                    if ($updateStmt->execute()) {
                        $success = 'Password updated successfully.';
                    } else {
                        $error = 'Failed to update password.';
                    }
                } else {
                    $error = 'New password and confirmation do not match.';
                }
            } else {
                $error = 'Current password is incorrect.';
            }
        } catch (PDOException $e) {
            $error = "Error updating password: " . $e->getMessage();
        }
    }

    // Save preferences
    if (isset($_POST['darkMode'], $_POST['notifications'])) {
        $themeMode = $_POST['darkMode'];
        $notifications = implode(',', $_POST['notifications']); // Convert array to a comma-separated string

        try {
            $prefStmt = $pdo->prepare('UPDATE Customer SET themeMode = :themeMode, notifications = :notifications WHERE customerID = :customerID');
            $prefStmt->bindParam(':themeMode', $themeMode, PDO::PARAM_STR);
            $prefStmt->bindParam(':notifications', $notifications, PDO::PARAM_STR);
            $prefStmt->bindParam(':customerID', $userID, PDO::PARAM_INT);

            if ($prefStmt->execute()) {
                $success = 'Preferences updated successfully.';
            } else {
                $error = 'Failed to update preferences.';
            }
        } catch (PDOException $e) {
            $error = "Error updating preferences: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .settings-container {
            max-width: 800px;
            margin: 100px auto 50px; /* Add more space at the top */
            padding: 30px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group input, 
        .form-group select {
            width: 100%; /* Ensure full width of the container */
            padding: 12px; /* Slightly larger padding for better spacing */
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .button-container {
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center the button horizontally */
            margin-top: 20px; /* Add some space above the button */
        }

        button {
            width: 35%; /* Optional: Make button stretch the container width */
            padding: 5px;
            background-color: #004a9f;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #003680;
        }

        .notification {
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Vertically center checkbox and label */
            gap: 10px; /* Add space between checkbox and text */
            margin-bottom: 10px; /* Add some spacing between options */
        }

        .notification input[type="checkbox"] {
            margin: 0; /* Remove default margins */
            width: 16px; /* Optional: Adjust checkbox size */
            height: 16px;
        }
        /* Default Light Theme */
        body.light {
            background-color: #ffffff;
            color: #000000;
        }

        .light .settings-container {
            background-color: #ffffff;
            color: #000000;
        }

        /* Dark Theme */
        body.dark {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .dark .settings-container {
            background-color: #333333;
            color: #ffffff;
        }

        /* Adjust other elements for dark mode if needed */
        .dark button {
            background-color: #444444;
        }

        .dark button:hover {
            background-color: #222222;
        }
    </style>

    <script>
        // Apply the theme immediately based on the current body class
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
            document.getElementById('darkMode').value = savedTheme;
        });

        // Toggle the theme dynamically
        function toggleMode() {
            const themeSelector = document.getElementById('darkMode');
            const selectedTheme = themeSelector.value;

            // Apply the selected theme to the body
            document.body.className = selectedTheme;

            // Optionally save the selected theme in localStorage (useful for immediate client-side storage)
            localStorage.setItem('themeMode', selectedTheme);
        }

        // Restore theme from localStorage when the page loads
        window.onload = function () {
            const savedTheme = localStorage.getItem('themeMode') || 'light'; // Default to light
            document.body.className = savedTheme;
        };
    </script>



</head>
<body class="<?php echo htmlspecialchars($themeMode); ?>">
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
                    <a href="settings.php">Settings Page</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="settings-container">
        <h1>Settings</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="settings.php">
            <!-- Change Password -->
            <div class="form-group">
                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password">
            </div>
            <div class="form-group">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm New Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
            </div><br>

            <!-- Dark/Light Mode -->
            <div class="form-group">
                <label for="darkMode">Mode:</label>
                <select id="darkMode" name="darkMode">
                    <option value="light" <?php echo $themeMode === 'light' ? 'selected' : ''; ?>>Light</option>
                    <option value="dark" <?php echo $themeMode === 'dark' ? 'selected' : ''; ?>>Dark</option>
                </select>
            </div><br>

            <!-- Notification Preferences -->
            <div class="form-group">
                <label>Notification Preferences:</label>
                <div class="notification">
                    <input type="checkbox" id="order" name="notifications[]" value="Order Updates" 
                        <?php echo in_array('Order Updates', $notifications) ? 'checked' : ''; ?>>
                    <label for="order">Order Updates</label>
                </div>
                <div class="notification">
                    <input type="checkbox" id="promo" name="notifications[]" value="Promotions" 
                        <?php echo in_array('Promotions', $notifications) ? 'checked' : ''; ?>>
                    <label for="promo">Promotions</label>
                </div>
            </div><br>

            <!-- Button Container -->
            <div class="button-container">
                <button type="submit" class="save-button">Save Changes</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        2018 All Rights Reserved KTM Berhad. KTM Berhad shall not be liable for any loss or damage caused by the usage of any 
        information obtained from this site. <br>
        © Copyright 2018 by <a href="#">KTM Berhad</a>
    </footer>
</body>
</html>
