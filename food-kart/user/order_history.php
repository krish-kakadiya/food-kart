<?php
// order_history.php
include '../backend/connection.php';
session_start();
$user_id = $_SESSION['user_id'];

// Fetch all orders for this user
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Your Order History</h1>
    <div class="orders">
        <?php while ($order = $orders->fetch_assoc()) { ?>
            <div class="order-card">
                <p>Order ID: <?php echo $order['id']; ?></p>
                <p>Date: <?php echo $order['order_date']; ?></p>
                <p>Status: <?php echo ucfirst($order['status']); ?></p>
                <h3>Items:</h3>
                
                <?php
                $order_id = $order['id'];
                $items = $conn->query("SELECT menu_items.name, order_items.quantity FROM order_items JOIN menu_items ON order_items.item_id = menu_items.id WHERE order_items.order_id = $order_id");
                while ($item = $items->fetch_assoc()) {
                    echo "<p>{$item['name']} - Quantity: {$item['quantity']}</p>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
