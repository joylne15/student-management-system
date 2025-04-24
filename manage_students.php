<?php
session_start();
include 'config.php'; // Ensure the database connection is correctly configured

// Ensure the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students
$students_result = $conn->query("SELECT * FROM user");

// Add student logic
if (isset($_POST['add_student'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new student into database
    $add_student_query = "INSERT INTO user (user_name, email, password, created_at) 
                          VALUES ('$username', '$email', '$hashed_password', NOW())";
    if ($conn->query($add_student_query)) {
        $_SESSION['message'] = "Student added successfully!";
    } else {
        $_SESSION['message'] = "Error adding student.";
    }

    // Redirect to avoid re-submission
    header("Location: manage_students.php");
    exit();
}

// Delete student logic
if (isset($_GET['delete_student_id'])) {
    $delete_student_id = $_GET['delete_student_id'];
    $delete_query = "DELETE FROM user WHERE user_id = '$delete_student_id'";
    if ($conn->query($delete_query)) {
        $_SESSION['message'] = "Student deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting student.";
    }
    // Redirect to avoid re-submission
    header("Location: manage_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .password-container {
            position: relative;
        }
        .password-container input[type="password"] {
            width: calc(100% - 30px);
            padding-right: 30px;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
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
        <h2>Manage Students</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <!-- Add Student Form -->
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
                <span class="eye-icon" onclick="togglePassword()">👁️</span>
            </div>
            <button type="submit" name="add_student">Add Student</button>
        </form>

        <!-- List of Students -->
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $students_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td>*****</td> <!-- We don't show the password, only "*****" -->
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="?delete_student_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;
        }
    </script>

</body>
</html>
