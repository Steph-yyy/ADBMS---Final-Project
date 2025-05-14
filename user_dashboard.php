<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_Login.php");
    exit();
}

// User session variables
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg'; // 'uploads/default.jpg' should exist
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #1e3d59;
            color: white;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .sidebar .profile p {
            margin-top: 12px;
            font-size: 18px;
            font-weight: bold;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            width: 80%;
            padding-bottom: 10px;
        }

        .sidebar a {
            width: 100%;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            text-align: left;
            transition: background 0.3s;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #0d2c47;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 50px;
            width: 100%;
        }

        .main-content h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .main-content p {
            color: #555;
            font-size: 16px;
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="profile">
        <img src="uploads/<?= htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
        <p><?= htmlspecialchars($username); ?></p>
    </div>
    <h2>Dashboard</h2>
    <a href="profile.php">👤 My Profile</a>
    <a href="Register.php">📅 Appointments</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="main-content">
    <h2>Hello, <?= htmlspecialchars($username); ?>!</h2>
    <p>Welcome to your dashboard. Use the menu on the left to navigate through your account features.</p>
</div>

</body>
</html>
