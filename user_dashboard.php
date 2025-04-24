<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user profile information
$query = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgba(57, 61, 65, 0.22), rgba(218, 226, 228, 0.15));
            display: flex;
            height: 100vh;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .sidebar {
            width: 220px;
            background-color:rgba(154, 108, 179, 0.44);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 12px 20px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color:rgb(95, 67, 15);
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
        }

        .welcome {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .profile-info {
            display: none;
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(201, 120, 44, 0.53);
        }

        .show-profile-btn {
            background-color:rgb(92, 79, 27);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .show-profile-btn:hover {
            background-color:rgba(154, 146, 146, 0.7);
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>User Panel</h2>
        <ul>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="courses.php">Available Courses</a></li>
            <li><a href="your_courses.php">Your Courses</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome">
            <h2>Welcome, <?php echo $user['user_name']; ?>!</h2>
        </div>
    </div>

</body>
</html>
