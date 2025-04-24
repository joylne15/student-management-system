<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Handle form submission (Save selected courses to the database)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courses'])) {
    $selected_courses = $_POST['courses'];

    foreach ($selected_courses as $course_id) {
        // Check if the course is already requested
        $check_query = "SELECT * FROM enrollment WHERE student_id = '$user_id' AND course_id = '$course_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // Insert into the enrollment table with 'Pending' status
            $insert_query = "INSERT INTO enrollment (student_id, course_id, enrollment_status) 
                             VALUES ('$user_id', '$course_id', 'Pending')";
            mysqli_query($conn, $insert_query);
        }
    }
}

// Fetch enrolled courses and the status
$enrollments_query = "SELECT c.course_name, e.enrollment_status 
                      FROM enrollment e 
                      JOIN courses c ON e.course_id = c.course_id 
                      WHERE e.student_id = '$user_id'";
$enrollments_result = mysqli_query($conn, $enrollments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Courses</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgba(57, 61, 65, 0.22), rgba(218, 226, 228, 0.15)); /* Same gradient as profile */
            display: flex;
            height: 100vh;
            justify-content: flex-start;
            align-items: flex-start;
            color: white;
        }

        .sidebar {
            width: 220px;
            background-color: rgba(154, 108, 179, 0.44); /* Sidebar color */
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
            color: white;
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
            background-color: rgb(95, 67, 15); /* Darker shade for hover */
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9); /* Light color container */
            padding: 20px;
            border-radius: 10px;
            color: black;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #9A6CB3; /* Heading color from the sidebar */
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: rgba(154, 108, 179, 0.44); /* Light color for table header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light grey color for even rows */
        }

        td span {
            font-weight: bold;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: rgb(92, 79, 27); /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: rgba(154, 146, 146, 0.7); /* Button hover effect */
        }

        .submit-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
        <div class="container">
            <h2>Your Enrolled Courses</h2>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Status</th>
                </tr>
                <?php while ($enrollment = mysqli_fetch_assoc($enrollments_result)): ?>
                    <tr>
                        <td><?php echo $enrollment['course_name']; ?></td>
                        <td>
                            <?php
                                if ($enrollment['enrollment_status'] == 'Pending') {
                                    echo '<span style="color: orange;">Pending</span>';
                                } elseif ($enrollment['enrollment_status'] == 'sorry, try next time!!') {
                                    echo '<span style="color: green;">Enrolled</span>';
                                } else {
                                    echo '<span style="color: red;">Enrolled</span>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
