<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courses'])) {
    $selected_courses = $_POST['courses'];
    
    foreach ($selected_courses as $course_id) {
        // Check if the student is already enrolled
        $check_query = "SELECT * FROM enrollment WHERE student_id = '$user_id' AND course_id = '$course_id'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) == 0) {
            // Insert new enrollment
            $insert_query = "INSERT INTO enrollment (student_id, course_id, enrollment_status) VALUES ('$user_id', '$course_id', 'Pending')";
            mysqli_query($conn, $insert_query);
        }
    }
    
    $_SESSION['message'] = "Enrollment request submitted successfully.";
    header("Location: enroll.php");
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
    <title>Enroll in Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #047857;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        form {
            text-align: center;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        input[type="submit"] {
            background-color: #047857;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #065f46;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enroll in Courses</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="enroll.php" method="POST">
            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                <label>
                    <input type="checkbox" name="courses[]" value="<?php echo $course['course_id']; ?>">
                    <?php echo $course['course_name']; ?>
                </label>
                <br>
            <?php endwhile; ?>
            <input type="submit" value="Enroll">
        </form>
    </div>
</body>
</html>
