<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['user_name']);
    $password = $_POST['password'];

    if (empty($user_name) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        $sql = "SELECT * FROM user WHERE user_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['role'] = 'user';
                header("Location: user_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Username not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
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
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .login-container {
            background: rgba(154, 108, 179, 0.44);
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }

        .error-message {
            color: rgb(174, 31, 31);
            font-weight: bold;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 15px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: rgb(92, 79, 27);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background-color: rgba(154, 146, 146, 0.7);
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: rgb(95, 67, 15);
            text-decoration: underline;
            font-weight: bold;
        }

        .footer {
            font-size: 12px;
            color: rgba(154, 146, 146, 0.7);
            margin-top: 20px;
        }

        .footer a {
            color: rgba(243, 235, 235, 0.24);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="user_name">Username:</label>
            <input type="text" name="user_name" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <button type="submit" class="login-button">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
