<?php
include 'config.php';

$error = $success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0; // Admin checkbox

    // Validation
    if (empty($user_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {  // Password strength check
        $error = "Password should be at least 6 characters long.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into appropriate table (admin or user)
        if ($is_admin) {
            $sql = "INSERT INTO admin (user_name, password, email) VALUES (?, ?, ?)";
        } else {
            $sql = "INSERT INTO user (user_name, password, email) VALUES (?, ?, ?)";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user_name, $hashed_password, $email);

        if ($stmt->execute()) {
            $success = "Registration successful!";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
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
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .registration-container {
            background: rgba(154, 108, 179, 0.44); /* Sidebar and header color */
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            color: white;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-top: 10px;
            color: white;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: rgb(92, 79, 27); /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: rgba(154, 146, 146, 0.7); /* Button hover effect */
        }

        .error, .success {
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }

        .error {
            background-color: rgb(108, 79, 11);
        }

        .success {
            background-color: rgba(136, 149, 160, 0.31);
        }

        p {
            text-align: center;
            font-size: 14px;
        }

        p a {
            color: rgb(46, 41, 41);
            text-decoration: underline;
            font-weight: bold;
        }

        p a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="registration-container">
        <h2>Register</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label for="user_name">Username:</label>
                <input type="text" name="user_name" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
