<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch restaurant owner details from the correct table
    $stmt = $conn->prepare("SELECT id, username, password FROM restaurant WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user details
        $restaurant = $result->fetch_assoc();

        // Check if the entered password matches the stored hashed password
        if (password_verify($password, $restaurant['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Store user data in session
            $_SESSION['restaurant_id'] = $restaurant['id'];
            $_SESSION['restaurant_username'] = $restaurant['username'];  // Storing restaurant username in session

            // Redirect to restaurant dashboard
            header("Location: ../restaurant/dashboard.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid username or password.";
        }
    } else {
        // Invalid username
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Login</title>
    <link rel="stylesheet" href="../assets/css/loginstyle.css">
</head>
<body>
    <div class="login-container">
        <h1>Restaurant Login</h1>

        <?php if (isset($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
