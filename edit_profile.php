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

// Fetch user details to pre-fill the form
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        // Update user details in the database
        try {
            $stmt = $pdo->prepare('UPDATE Customer SET name = :name, email = :email, phone = :phone WHERE customerID = :customerID');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':customerID', $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success = 'Profile updated successfully.';
                // Update the current session data if needed
                $user['name'] = $name;
                $user['email'] = $email;
                $user['phone'] = $phone;
            } else {
                $error = 'Failed to update profile.';
            }
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
            text-align: left;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #004a9f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #003366;
        }

        .message {
            text-align: center;
            margin: 10px 0;
            font-size: 16px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Profile</h1>
        <?php if ($error): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="POST" action="edit_profile.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
