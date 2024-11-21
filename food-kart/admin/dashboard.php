<?php
session_start();

// Redirect to login if not logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();  // Destroy the session to log out
    header('Location: admin_login.php');  // Redirect to login page
    exit;
}

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=food_kart', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch all users
    $stmt_users = $pdo->query("SELECT * FROM user");
    $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all restaurants
    $stmt_restaurants = $pdo->query("SELECT * FROM restaurant");
    $restaurants = $stmt_restaurants->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all menu items
    $stmt_menu = $pdo->query("SELECT * FROM menu_item");
    $menu_items = $stmt_menu->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->
    <style>
        /* Body and Background Image */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../assets/images/food1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #fff;
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
        }

        .logout {
            background-color: #f44336;
            color: white;
            padding: 12px 18px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .logout:hover {
            background-color: #e53935;
        }

        /* Card Layout */
        .card-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #333;
            color: #fff;
            width: 23%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card i {
            font-size: 50px;
            margin-bottom: 20px;
            color: #ffc107;
        }

        .card h3 {
            font-size: 20px;
            margin: 10px 0;
        }

        .card a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        .card a:hover {
            color: #ffc107;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                width: 48%;
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Logout Link -->
        <a href="?logout=true" class="logout">Logout</a>

        <div class="card-container">
            <!-- Manage Users Card -->
            <div class="card">
                <i class="fas fa-users"></i> <!-- Font Awesome Icon -->
                <h3>Manage Users</h3>
                <a href="manage_users.php">View All Users</a>
            </div>

            <!-- Manage Restaurants Card -->
            <div class="card">
                <i class="fas fa-utensils"></i> <!-- Font Awesome Icon -->
                <h3>Manage Restaurants</h3>
                <a href="manage_restaurants.php">View All Restaurants</a>
            </div>

            <!-- Manage Menu Items Card -->
            <div class="card">
                <i class="fas fa-list-ul"></i> <!-- Font Awesome Icon -->
                <h3>Manage Menu Items</h3>
                <a href="manage_menu.php">View All Menu Items</a>
            </div>

            <!-- Dashboard Summary Card -->
            <div class="card">
                <i class="fas fa-tachometer-alt"></i> <!-- Font Awesome Icon -->
                <h3>Dashboard Summary</h3>
                <ul>
                    <li>Total Users: <?php echo count($users); ?></li>
                    <li>Total Restaurants: <?php echo count($restaurants); ?></li>
                    <li>Total Menu Items: <?php echo count($menu_items); ?></li>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>
