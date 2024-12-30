<?php
// Include the database connection file
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;

    $sql = "INSERT INTO feedback (customer_name, email, contact, feedback, rating) VALUES ('$name', '$email', '$contact', '$feedback', $rating)";

    if ($conn->query($sql) === TRUE) {
        $message = "Thank you for your feedback!";
    } else {
        $message = "Error: " . $sql . "\n" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    
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

        .content {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
        }

        .content h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .feedback-form {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .feedback-form input, .feedback-form textarea, .feedback-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .feedback-form input:focus, .feedback-form textarea:focus {
            border-color: #004a9f;
            outline: none;
        }

        .feedback-form button {
            background-color: #004a9f;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .feedback-form button:hover {
            background-color: #003380;
        }

        .rating {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .rating span {
            font-size: 30px;
            cursor: pointer;
            color: #ccc;
            margin: 0 5px;
            transition: color 0.3s;
        }

        .rating span.active {
            color: #ffd700;
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
            .feedback-form {
                width: 90%;
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll(".rating span");
            const ratingInput = document.querySelector("input[name='rating']");

            stars.forEach((star, index) => {
                star.addEventListener("click", () => {
                    ratingInput.value = index + 1;
                    stars.forEach((s, i) => {
                        s.classList.toggle("active", i <= index);
                    });
                });
            });
        });
    </script>
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
                    <a href="order_history.html">Order History</a>
                    <a href="feedback_user.php">Feedback Page</a>
                    <a href="settings.html">Settings Page</a>
                    <a href="logout.php">Logout</a>
                </div>
    </header>

    <div class="content">
        <h1>Your Feedback Is Important For Us</h1>
        <?php if (isset($message)) { echo "<p style='color: green;'>$message</p>"; } ?>
        <form class="feedback-form" method="POST" action="">
            <input type="text" name="name" placeholder="Enter Your Name" required>
            <input type="email" name="email" placeholder="Enter email" required>
            <input type="tel" name="contact" placeholder="Enter your contact number (Optional)">
            <textarea name="feedback" rows="5" placeholder="Write your feedback here..." required></textarea>
            <label>Please rate us:</label>
            <div class="rating">
                <span class="star" data-rating="1">&#9733;</span>
                <span class="star" data-rating="2">&#9733;</span>
                <span class="star" data-rating="3">&#9733;</span>
                <span class="star" data-rating="4">&#9733;</span>
                <span class="star" data-rating="5">&#9733;</span>
            </div>
            <input type="hidden" name="rating" value="0">
            <button type="submit">Submit</button>
        </form>
    </div>

    <footer class="footer">
        2018 All Rights Reserved KTM Berhad. KTM Berhad shall not be liable for any loss or damage caused by the usage of any 
        information obtained from this site. <br>
        &copy; Copyright 2018 by <a href="#">KTM Berhad</a>
    </footer>
</body>
</html>
