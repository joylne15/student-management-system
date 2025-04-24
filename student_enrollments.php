<?php
session_start();
include 'config.php';

if (isset($_POST['enroll_student'])) {
    $student_id = $_POST['student_id'] ?? '';
    $course_id = $_POST['course_id'] ?? '';

    if (!empty($student_id) && !empty($course_id)) {
        $stmt = $conn->prepare("INSERT INTO enrollment (`student_id`, `course_id`, enrollment_date, `enrollment_status`) VALUES (?, ?, NOW(), 'Active')");
        $stmt->bind_param("is", $student_id, $course_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Please select both student and course.</p>";
    }
}

if (isset($_GET['delete_enrollment'])) {
    $enrollment_id = $_GET['delete_enrollment'];

    if (is_numeric($enrollment_id)) {
        $stmt = $conn->prepare("DELETE FROM enrollment WHERE `Enrollment_id` = ?");
        $stmt->bind_param("i", $enrollment_id);

        if ($stmt->execute()) {
            echo "<script>alert('Enrollment deleted successfully.');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid enrollment_ID.</p>";
    }
}

$students = $conn->query("SELECT * FROM user");
$courses = $conn->query("SELECT * FROM courses");
$enrollments = $conn->query("SELECT 
                                enrollment.`Enrollment_id`, 
                                user.user_name, 
                                courses.`course_name`, 
                                enrollment.enrollment_date, 
                                enrollment.`enrollment_status`
                             FROM enrollment
                             JOIN user ON enrollment.`student_id` = user.id
                             JOIN courses ON enrollment.`course_id` = courses.`course_id`");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9f5e4;
            color: #2d6a4f;
        }
        h2 {
            background-color: #047857;
            color: white;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            margin-top: 0;
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
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        select, button {
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #047857;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #065f46;
        }
        select {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }
        .error, .success {
            padding: 10px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
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
    <h2>Enroll Student in a Course</h2>

    <div class="form-container">
        <form method="POST">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>">
                        <?php echo htmlspecialchars($row['user_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['course_id']); ?>">
                        <?php echo htmlspecialchars($row['course_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="enroll_student">Enroll Student</button>
        </form>
    </div>

    <h2>Manage Enrollments</h2>
    <table>
        <tr>
            <th>Enrollment ID</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Enrollment Date</th>
            <th>Enrollment Status</th>
            <th>Action</th>
        </tr>
        <?php while ($enrollment = $enrollments->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($enrollment['Enrollment_id']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['user_name']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['enrollment_date']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['enrollment_status']); ?></td>
                <td>
                    <a href="?delete_enrollment=<?php echo $enrollment['Enrollment_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this enrollment?');">Delete</a>
                  <td> <a href="?confirm_enrollment=<?php echo $enrollment['Enrollment_id']; ?>" >
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
