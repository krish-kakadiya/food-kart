<?php
session_start();
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: ../backend/restaurant_login.php");
    exit();
}

include '../backend/connection.php'; // Database connection

$restaurant_id = $_SESSION['restaurant_id'];

// Check if the order_date column exists
$result = $conn->query("SHOW COLUMNS FROM orders LIKE 'order_date'");
$column_exists = $result->num_rows > 0;

// Fetch all orders for the restaurant, joining with the user table to get customer name
if ($column_exists) {
    $query = "
        SELECT orders.*, user.username AS customer_name
        FROM orders
        LEFT JOIN user ON orders.user_id = user.id
        WHERE orders.restaurant_id = $restaurant_id
        ORDER BY orders.order_date DESC
    ";
    $orders = $conn->query($query);
} else {
    $query = "
        SELECT orders.*, user.username AS customer_name
        FROM orders
        LEFT JOIN user ON orders.user_id = user.id
        WHERE orders.restaurant_id = $restaurant_id
    ";
    $orders = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="../assets/css/view_orders.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>View Orders</h1>
        <p>Here, you can see all the orders placed by customers.</p>

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td> <!-- Fetching customer name -->
                    <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['items'] ?? 'No items listed'); ?></td> <!-- Handle undefined "items" column -->
                    <td>
                        <?php 
                        if (isset($row['total_amount']) && $row['total_amount'] !== NULL) {
                            echo '$' . number_format($row['total_amount'], 2);
                        } else {
                            echo '$0.00';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form action="update_order_status.php" method="post" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="status-select">
                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="In Progress" <?php echo $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
