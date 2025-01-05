<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ets_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists in the admin table
    $adminQuery = $conn->prepare("SELECT * FROM Admin WHERE email = ?");
    $adminQuery->bind_param("s", $email);
    $adminQuery->execute();
    $adminResult = $adminQuery->get_result();

    if ($adminResult->num_rows > 0) {
        $admin = $adminResult->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['user'] = $admin['name'];
            $_SESSION['role'] = 'admin';
            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            exit();
        } else {
            echo "Invalid password for admin.";
        }
    }

    // Check if user exists in the Customer table
    $userQuery = $conn->prepare("SELECT * FROM Customer WHERE email = ?");
    $userQuery->bind_param("s", $email);
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['name'];
            $_SESSION['role'] = 'user';
            header('Location: user_dashboard.php'); // Redirect to user dashboard
            exit();
        } else {
            echo "Invalid password for user.";
        }
    }

    echo "Invalid login credentials.";
}

$conn->close();
?>