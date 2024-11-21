<?php
session_start();
include '../backend/connection.php';

// Check if order_id is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the database
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        
        // Display order details
        echo "<h1>Order Confirmation</h1>";
        echo "<p><strong>Order ID:</strong> " . htmlspecialchars($order['id']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($order['status']) . "</p>";
        echo "<p><strong>Total Amount:</strong> $" . number_format($order['total_amount'], 2) . "</p>";
        echo "<h3>Order Details:</h3>";

        // Decode and display the order items (order_data is in JSON format)
        $order_data = json_decode($order['order_data'], true);
        echo "<ul>";
        foreach ($order_data as $item) {
            echo "<li>";
            echo "Item: " . htmlspecialchars($item['name']) . " | Price: $" . htmlspecialchars($item['price']) . " | Quantity: " . htmlspecialchars($item['quantity']);
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Error: Order not found.";
    }

    $stmt->close();
} else {
    echo "Error: Order ID not provided.";
}

$conn->close();
?>
