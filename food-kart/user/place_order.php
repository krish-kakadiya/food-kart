<?php
session_start();
include '../backend/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if order data is passed
if (!isset($_POST['order_data']) || empty($_POST['order_data'])) {
    echo "Error: Your cart is empty.";
    exit;
}

$order_data = json_decode($_POST['order_data'], true);

// Calculate total amount
$total_amount = 0;
foreach ($order_data as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Get restaurant_id (Assuming all items belong to the same restaurant)
$restaurant_id = $order_data[0]['restaurant_id'];

// Insert the order into the database
$stmt = $conn->prepare("INSERT INTO orders (user_id, restaurant_id, order_data, total_amount, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->bind_param("iisi", $user_id, $restaurant_id, $_POST['order_data'], $total_amount);

if ($stmt->execute()) {
    // Order placed successfully, get the order ID
    $order_id = $stmt->insert_id; // Get the last inserted order ID
    
    // Clear the cart session
    unset($_SESSION['cart']);  // Empty the session cart after placing the order

    // Redirect to the confirmation page and pass the order ID in the URL
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
