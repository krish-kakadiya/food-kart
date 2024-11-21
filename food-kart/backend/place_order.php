<?php
session_start();
include '../backend/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: You must be logged in to place an order.";
    exit;
}

// Check if cart data is passed from the form
if (empty($_POST['order_data'])) {
    echo "Error: Your cart is empty.";
    exit;
}

$user_id = $_SESSION['user_id'];
$order_data = $_POST['order_data'];  // e.g., JSON string of order items

// Decode the order data to check if it's coming correctly
$cart_items = json_decode($order_data, true);

// Debug: Check the decoded order data
echo "<pre>";
print_r($cart_items);  // Check the decoded order items
echo "</pre>";

if (empty($cart_items)) {
    echo "Error: Invalid order data.";
    exit;
}

// Assume a restaurant_id is selected, you can update this logic
$restaurant_id = $_POST['restaurant_id'] ?? null;

if (!$restaurant_id) {
    echo "Error: Restaurant ID is missing.";
    exit;
}

// Insert the order into the database
$stmt = $conn->prepare("INSERT INTO orders (user_id, restaurant_id, order_data) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $restaurant_id, $order_data);

if ($stmt->execute()) {
    echo "Order placed successfully!";
    // Optionally, clear the cart after placing the order (if desired)
    // unset($_SESSION['cart']);
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
