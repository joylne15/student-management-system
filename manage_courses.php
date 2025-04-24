<?php
session_start();
include 'config.php';  // Ensure this file has correct DB connection

if (isset($_POST['add_course'])) {
    $course_id = $_POST['course_id'] ?? '';  // Course ID as a string
    $course_name = $_POST['course_name'] ?? '';  // Course Name
    $department = $_POST['department'] ?? '';  // Department
    $duration = $_POST['duration'] ?? '';  // Duration as a string

    if (!empty($course_id) && !empty($course_name) && !empty($department) && !empty($duration)) {
        // Check if the course ID already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM courses WHERE `course id` = ?");
        $checkStmt->bind_param("s", $course_id);  // Binding course_id as a string
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count == 0) {
            // Insert the course into the database
            $stmt = $conn->prepare("INSERT INTO courses (`course id`, `course name`, `department`, `duration`) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $course_id, $course_name, $department, $duration);  // Binding all values as strings
            $stmt->execute();
            $stmt->close();

            // Redirect to avoid form resubmission on page reload
            header("Location: manage_courses.php");
            exit;
        } else {
            echo "Course ID already exists!";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE `course id` = ?");
    $stmt->bind_param("s", $course_id);  // Binding course_id as a string
    $stmt->execute();
}

$courses = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f5e4;
            margin: 0;
            padding: 0;
            color: #2d6a4f;
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
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #047857;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #065f46;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #047857;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-delete {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-delete:hover {
            background-color: #d43f3a;
        }
    </style>
</head>
<body>

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

    <div class="main-content">
        <h2>Manage Courses</h2>

        <!-- Course Add Form -->
        <form method="POST">
            <input type="text" name="course_id" placeholder="Enter Course ID" required>
            <input type="text" name="course_name" placeholder="Enter Course Name" required>
            <input type="text" name="department" placeholder="Enter Department" required>
            <input type="text" name="duration" placeholder="Enter Duration" required>
            <button type="submit" name="add_course">Add Course</button>
        </form>

        <!-- Course List Table -->
        <table>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Department</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['course_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td>
                        <a href="manage_courses.php?delete=<?php echo $row['course_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

    </div>

</body>
</html>
