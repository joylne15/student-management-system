<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h2 {
            background-color: #047857;
            color: white;
            padding: 15px;
            margin: 0;
            font-size: 24px;
        }
        .sidebar {
            width: 220px;
            background-color: #047857;
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
            font-size: 22px;
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
            background-color: #065f46;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="manage_students.php">Manage Students</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="request.php">Requests</a></li> <!-- New Request link -->
            <li><a href="student_enrollments.php">Manage Enrollments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    </div>
</body>
</html>
