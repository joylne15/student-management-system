<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php"); 
    exit();
}

// Fetch available courses
$courses_query = "SELECT * FROM courses";
$courses_result = mysqli_query($conn, $courses_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgba(57, 61, 65, 0.22), rgba(218, 226, 228, 0.15)); /* Gradient background similar to profile */
            display: flex;
            height: 100vh;
            justify-content: flex-start;
            align-items: flex-start;
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
            background: rgba(255, 240, 245, 0.9); /* Light pink background similar to profile */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: black; /* Green color for heading */
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
            background-color: rgba(154, 108, 179, 0.44); /* Light purple for table header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light grey color for even rows */
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
    <script>
        function limitSelection() {
            let checkboxes = document.querySelectorAll('input[name="courses[]"]');
            let checkedCount = 0;
            
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    checkedCount++;
                }
            });
            
            if (checkedCount >= 4) {
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        checkbox.disabled = true;
                    }
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                });
            }
        }
    </script>
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
            <h2>Available Courses</h2>
            <form action="your_courses.php" method="POST">
                <table>
                    <tr>
                        <th>Select</th>
                        <th>Course ID</th>
                        <th>Course Name</th>
                    </tr>
                    <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                        <tr>
                            <td><input type="checkbox" name="courses[]" value="<?php echo $course['course_id']; ?>" onclick="limitSelection()"></td>
                            <td><?php echo $course['course_id']; ?></td>
                            <td><?php echo $course['course_name']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <br>
                <input type="submit" value="Submit" class="submit-btn">
            </form>
        </div>
    </div>
</body>
</html>
