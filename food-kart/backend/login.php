<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/loginstyle.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <p>Please enter your username and password.</p>

        <?php
        include 'connection.php';
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: ../user/dashboard.php");
                    exit;
                } else {
                    echo "<div class='error-message'>Invalid password.</div>";
                }
            } else {
                echo "<div class='error-message'>No user found.</div>";
            }

            $stmt->close();
        }
        ?>

        <!-- Login Form -->
        <form method="POST" action="login.php">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>

        <div class="forgot-password">
            <a href="#">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
