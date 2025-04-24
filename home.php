<?php
session_start();
include 'config.php'; // Ensure database connection

// Ensure the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetching total counts dynamically from the database
$total_courses_query = $conn->query("SELECT COUNT(*) AS total_courses FROM courses");
$total_courses_result = $total_courses_query->fetch_assoc();
$total_courses = $total_courses_result['total_courses'];

$total_enrollments_query = $conn->query("SELECT COUNT(*) AS total_enrollments FROM enrollment");
$total_enrollments_result = $total_enrollments_query->fetch_assoc();
$total_enrollments = $total_enrollments_result['total_enrollments'];

// Fetching total number of students (instead of listing names)
$total_students_query = $conn->query("SELECT COUNT(*) AS total_students FROM user");
$total_students_result = $total_students_query->fetch_assoc();
$total_students = $total_students_result['total_students'];

// Fetching latest courses
$courses_result = $conn->query("SELECT * FROM courses LIMIT 5");

// Fetching latest enrollments
$enrollments_result = $conn->query("SELECT * FROM enrollment LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Admin Panel</title>
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
        .dashboard {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 10px;
            padding: 20px;
            width: 250px;
            text-align: center;
            border-left: 5px solid #047857;
        }
        .card h3 {
            margin: 0;
            color: #047857;
        }
        .card p {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn {
            background-color: #047857;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #065f46;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="manage_students.php">Manage Students</a></li>
            <li><a href="request.php">Requests</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="student_enrollments.php">Manage Enrollments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        
        <div class="dashboard">
            <!-- Total Courses -->
            <div class="card">
                <h3>Total Courses</h3>
                <p><?php echo $total_courses; ?></p>
            </div>
            <!-- Total Enrollments -->
            <div class="card">
                <h3>Total Enrollments</h3>
                <p><?php echo $total_enrollments; ?></p>
            </div>
            <!-- Total Students -->
            <div class="card">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
            <!-- Existing Courses -->
            <div class="card">
                <h3>Courses</h3>
                <?php while ($row = $courses_result->fetch_assoc()) {
                    echo "<p>" . htmlspecialchars($row['course_name']) . "</p>";
                } ?>
                <a href="manage_courses.php" class="btn">Manage Courses</a>
            </div>
            <!-- Enrollments -->
            <div class="card">
                <h3>Enrollments</h3>
                <?php while ($row = $enrollments_result->fetch_assoc()) {
                    echo "<p>Student: " . htmlspecialchars($row['student_id']) . " - Course: " . htmlspecialchars($row['course_id']) . "</p>";
                } ?>
                <a href="student_enrollments.php" class="btn">View Enrollments</a>
            </div>
        </div>
    </div>
</body>
</html>
