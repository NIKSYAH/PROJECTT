<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ets_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die('Both email and password are required.');
    }

    // Admin login check
    $adminQuery = $conn->prepare("SELECT username, password FROM Admin WHERE username = ?");
    $adminQuery->bind_param("s", $email);
    $adminQuery->execute();
    $adminResult = $adminQuery->get_result();

    if ($adminResult->num_rows > 0) {
        $admin = $adminResult->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['user'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password for admin.');</script>";
        }
    }

    $adminQuery->close();

    // Customer login check
    $userQuery = $conn->prepare("SELECT name, password FROM Customer WHERE email = ?");
    $userQuery->bind_param("s", $email);
    $userQuery->execute();
    $userResult = $userQuery->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['name'];
            $_SESSION['role'] = 'customer';
            header('Location: user_dashboard.php'); // Redirect to user dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password for customer.');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.');</script>";
    }

    $userQuery->close();
}

$conn->close();
?>
