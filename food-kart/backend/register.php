<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if the role is "restaurant" to get the restaurant name
    if ($role == 'restaurant') {
        $restaurant_name = $_POST['restaurant_name'];

        // Insert into the restaurant table
        $stmt = $conn->prepare("INSERT INTO restaurant (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $restaurant_name, $username, $email, $password);
    } else {
        // Insert into the user table
        $stmt = $conn->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
    }

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Registration successful!</p>";
        header("Location: " . ($role == 'restaurant' ? "restaurant_login.php" : "login.php"));
        exit();
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
<div class="registration-container">
    <h1>Register</h1>
    <form method="POST" action="register.php">
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Register as:</label>
        <select name="role" id="role" required onchange="toggleRestaurantNameField()">
            <option value="user">User</option>
            <option value="restaurant">Restaurant</option>
        </select>

        <!-- Restaurant Name Field, shown only if "Restaurant" is selected -->
        <div id="restaurant-name-field" style="display: none;">
            <label>Restaurant Name:</label>
            <input type="text" name="restaurant_name">
        </div>

        <button type="submit">Register</button>
    </form>
    <div class="forgot-password">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script>
    // Show the restaurant name field only when "Restaurant" role is selected
    function toggleRestaurantNameField() {
        const roleSelect = document.getElementById('role');
        const restaurantNameField = document.getElementById('restaurant-name-field');
        restaurantNameField.style.display = roleSelect.value === 'restaurant' ? 'block' : 'none';
    }
</script>
</body>
</html>
