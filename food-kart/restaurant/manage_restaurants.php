<?php
session_start();
include '../backend/connection.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

// Fetch all restaurants from the database
$restaurants_sql = "SELECT * FROM restaurant";
$restaurants_result = $conn->query($restaurants_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Restaurants - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/manage_restaurants.css">
</head>
<body>
    <div class="container">
        <h1>Manage Restaurants</h1>
        <a href="add_restaurant.php" class="add-button">Add New Restaurant</a>

        <!-- Restaurant List -->
        <table class="restaurant-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Restaurant Name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($restaurant = $restaurants_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                        <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                        <td><?php echo htmlspecialchars($restaurant['username']); ?></td>
                        <td>
                            <a href="edit_restaurant.php?restaurant_id=<?php echo $restaurant['id']; ?>" class="edit-button">Edit</a>
                            <a href="delete_restaurant.php?restaurant_id=<?php echo $restaurant['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this restaurant?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
