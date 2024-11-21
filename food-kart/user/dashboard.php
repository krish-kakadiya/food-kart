<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

include '../backend/connection.php';  // Include the database connection

// Fetch the user information based on the user ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch all restaurants
$restaurants_sql = "SELECT * FROM restaurant";
$restaurants_result = $conn->query($restaurants_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/user_dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <div class="header-icons">
                <i class="fa-solid fa-user profile-icon" onclick="toggleSidebar()"></i>
                <a href="../user/cart.php"><i class="fa-solid fa-cart-shopping cart-icon"></i></a>
            </div>
        </header>

        <!-- Profile Sidebar -->
        <div id="profile-sidebar" class="sidebar">
            <div class="sidebar-content">
                <span class="close-btn" onclick="toggleSidebar()">&times;</span>
                <h2>Your Profile</h2>
                <p><strong>Name:</strong> <?php echo isset($user['name']) ? htmlspecialchars($user['name']) : 'N/A'; ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p> <!-- Display user's address -->
                <a href="edit_profile.php" class="button">Edit Profile</a>
            </div>
        </div>

        <section class="restaurants">
            <h2>Available Restaurants</h2>

            <?php while ($restaurant = $restaurants_result->fetch_assoc()): ?>
                <div class="restaurant">
                    <h3><?php echo htmlspecialchars($restaurant['name']); ?></h3> <!-- Restaurant Name -->
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($restaurant['address']); ?></p> <!-- Display restaurant's address -->

                    <h4>Menu</h4>
                    <?php
                    // Fetch the menu items for the current restaurant
                    $menu_stmt = $conn->prepare("SELECT * FROM menu_item WHERE restaurant_id = ?");
                    $menu_stmt->bind_param("i", $restaurant['id']);
                    $menu_stmt->execute();
                    $menu_result = $menu_stmt->get_result();
                    
                    if ($menu_result->num_rows > 0) {
                        echo '<div class="menu-items">';
                        while ($menu_item = $menu_result->fetch_assoc()) {
                            echo '<div class="menu-item">';
                            
                            // Ensure the image path is correct
                            $image_path = '../assets/images/menu/' . htmlspecialchars($menu_item['image']);
                            
                            // Check if the image exists and display it
                            if (file_exists($image_path)) {
                                echo '<img src="' . $image_path . '" alt="' . htmlspecialchars($menu_item['name']) . '" class="menu-item-image">';
                            } else {
                                echo '<img src="../assets/images/default.jpg" alt="No Image Available" class="menu-item-image">';
                            }

                            echo '<div class="menu-item-details">';
                            echo '<h5>' . htmlspecialchars($menu_item['name']) . '</h5>';
                            echo '<p>' . htmlspecialchars($menu_item['description']) . '</p>';
                            echo '<p><strong>$' . number_format($menu_item['price'], 2) . '</strong></p>';
                            echo '<button class="add-to-cart-btn" onclick="addToCart(' . $menu_item['id'] . ')">Add to Cart</button>';
                            echo '</div></div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p>No menu items available.</p>';
                    }
                    ?>
                </div>
            <?php endwhile; ?>
        </section>

        <section class="logout">
            <a href="logout.php" class="logout-button">Logout</a>
        </section>
    </div>

    <script src="../assets/js/cart.js"></script>
</body>
</html>
