<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

$query = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['user_name'];
    $new_email = $_POST['email'];
    
    $update_query = "UPDATE user SET user_name='$new_username', email='$new_email' WHERE id='$user_id'";
    mysqli_query($conn, $update_query);
    
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}
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
            color: white;
        }

        .sidebar {
            width: 220px;
            background-color: rgba(154, 108, 179, 0.44);
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
            background-color: rgb(95, 67, 15);
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
            background-color: #fff;
        }

        .welcome {
            background-color: rgba(255, 240, 245, 0.9);
            padding: 20px;
            border-radius: 10px;
            color: black;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .profile-info {
            background-color: rgba(255, 240, 245, 0.9);
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            color: black;
            box-shadow: 0px 4px 10px rgba(201, 120, 44, 0.53);
        }

        .toggle-password {
            cursor: pointer;
            margin-left: 10px;
            color: rgb(92, 79, 27);
        }

        button {
            background-color: rgb(92, 79, 27);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: rgba(154, 146, 146, 0.7);
        }
    </style>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</head>
<body>

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

    <div class="main-content">
        <div class="welcome">
            <h2>Welcome, <?php echo $user['user_name']; ?>!</h2>
        </div>

        <div class="profile-info">
            <h3>Update Profile</h3>
            <form method="POST">
                <label>User Name:</label>
                <input type="text" name="user_name" value="<?php echo $user['user_name']; ?>" required><br><br>
                
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>
                
                <label>Password:</label>
                <input type="password" id="password" value="**********" readonly>
                <span class="toggle-password" onclick="togglePassword()">👁️</span><br><br>
                
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
