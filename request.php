<?php
session_start();
include 'config.php';

if (isset($_GET['action']) && isset($_GET['enrollment_id'])) {
    $enrollment_id = $_GET['enrollment_id'];
    $action = $_GET['action'];

    if ($action == 'enroll' || $action == 'reject') {
        $status = ($action == 'enroll') ? 'Enrolled' : 'Rejected';
        $enrollment_date = ($action == 'enroll') ? date('Y-m-d H:i:s') : NULL;

        $stmt = $conn->prepare("UPDATE enrollment SET enrollment_status = ?, enrollment_date = ? WHERE Enrollment_id = ?");
        $stmt->bind_param("ssi", $status, $enrollment_date, $enrollment_id);

        if ($stmt->execute()) {
            echo "<script>alert('Enrollment " . $status . " successfully.'); window.location.href='student_enrollments.php';</script>";
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM enrollment WHERE Enrollment_id = ?");
        $stmt->bind_param("i", $enrollment_id);

        if ($stmt->execute()) {
            echo "<script>alert('Enrollment deleted successfully.'); window.location.href='student_enrollments.php';</script>";
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
    }
}

$enrollments = $conn->query("SELECT user.user_name, GROUP_CONCAT(courses.course_name) AS courses, GROUP_CONCAT(enrollment.Enrollment_id) AS enrollment_ids, MAX(enrollment.enrollment_date) AS enrollment_date, MAX(enrollment.enrollment_status) AS enrollment_status FROM enrollment JOIN user ON enrollment.student_id = user.id JOIN courses ON enrollment.course_id = courses.course_id GROUP BY user.user_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Management</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-color: #f0f0f0; color: #333; }
        h2 { background-color: #047857; color: white; padding: 15px; font-size: 24px; text-align: center; margin-top: 0; }
        .sidebar { width: 220px; background-color: #047857; color: white; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 22px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { color: white; text-decoration: none; font-size: 18px; display: block; padding: 12px 20px; border-radius: 5px; text-align: center; transition: background-color 0.3s; }
        .sidebar ul li a:hover { background-color: #065f46; }
        .main-content { margin-left: 240px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #047857; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-confirm, .btn-reject, .btn-delete { padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 14px; margin: 5px 0; cursor: pointer; }
        .btn-confirm { background-color: green; color: white; }
        .btn-confirm:hover { background-color: #4CAF50; }
        .btn-reject { background-color: red; color: white; }
        .btn-reject:hover { background-color: #d43f3a; }
        .btn-delete { background-color: #d9534f; color: white; }
        .btn-delete:hover { background-color: #c9302c; }
        .status { font-weight: bold; }
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
    <h2>Manage Enrollments</h2>
    <table>
        <tr>
            <th>Student Name</th>
            <th>Courses</th>
            <th>Enrollment Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($enrollment = $enrollments->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($enrollment['user_name']); ?></td>
                <td>
                    <form action="request.php" method="GET">
                        <select name="enrollment_id">
                            <?php
                            $course_names = explode(",", $enrollment['courses']);
                            $enrollment_ids = explode(",", $enrollment['enrollment_ids']);
                            foreach ($course_names as $index => $course) {
                                echo "<option value='{$enrollment_ids[$index]}'>{$course}</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="action" value="enroll" class="btn-confirm" onclick="return confirm('Are you sure you want to enroll this student in the selected course?');">
                    </form>
                </td>
                <td><?php echo htmlspecialchars($enrollment['enrollment_date']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['enrollment_status']); ?></td>
                <td>
                    <button onclick="window.location.href='?action=reject&enrollment_id=<?php echo $enrollment['enrollment_ids']; ?>'" class="btn-reject">Reject</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
