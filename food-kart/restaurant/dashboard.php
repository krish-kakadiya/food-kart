<?php
session_start();
include '../backend/connection.php';

// Check if the restaurant owner is logged in by verifying `restaurant_id` in the session
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: ../backend/restaurant_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to Your Dashboard</h1>
        <?php if (isset($_SESSION['restaurant_username'])): ?>
            <p  class="welcome-message">Hello, <?php echo htmlspecialchars($_SESSION['restaurant_username']); ?>! Manage your restaurant below.</p>
        <?php endif; ?>

        <div class="dashboard-cards">
            <!-- Manage Menu Card -->
            <div class="card">
                <img src="../assets/images/menu-icon.png" alt="Manage Menu">
                <h2>Manage Menu</h2>
                <p>Update and add new items to your restaurant's menu.</p>
                <a href="manage_menu.php" class="button">Go to Menu</a>
            </div>
            
            <!-- View Orders Card -->
            <div class="card">
                <img src="../assets/images/orders-icon.png" alt="View Orders">
                <h2>View Orders</h2>
                <p>Check and manage orders placed by customers.</p>
                <a href="view_orders.php" class="button">View Orders</a>
            </div>

            <!-- Edit Restaurant Card -->
            <div class="card">
                <img src="../assets/images/edit.png" alt="Edit Restaurant">
                <h2>Edit Restaurant</h2>
                <p>Edit your restaurant's profile, including name, address, and other details.</p>
                <a href="edit_restaurant.php" class="button">Edit Profile</a>
            </div>

            <!-- Delete Restaurant Card -->
            <div class="card">
                <img src="../assets/images/delete.jpg" alt="Delete Restaurant">
                <h2>Delete Restaurant</h2>
                <p>Permanently remove your restaurant from the system.</p>
                <a href="../user/delete_restaurant.php" class="button delete" onclick="return confirm('Are you sure you want to delete your restaurant? This action cannot be undone.')">Delete Restaurant</a>
            </div>

            <!-- Logout Card -->
            <div class="card">
                <img src="../assets/images/logout-icon.png" alt="Logout">
                <h2>Logout</h2>
                <p>End your session and logout from the dashboard.</p>
                <a href="../backend/logout.php" class="button logout">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
