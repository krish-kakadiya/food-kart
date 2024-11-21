<?php
session_start();

// Check if cart data exists in session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
} else {
    echo "<div class='empty-cart'>Your cart is empty.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }
        .cart-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .cart-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item .item-details {
            font-size: 16px;
        }
        .cart-item .item-price {
            font-weight: bold;
            color: #333;
        }
        .cart-total {
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
        .place-order-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .place-order-btn:hover {
            background-color: #45a049;
        }
        .empty-cart {
            font-size: 20px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <div class="cart-header">Your Cart</div>

    <?php
    $total = 0;
    foreach ($cart_items as $item) {
        echo '<div class="cart-item">';
        echo '<div class="item-details">Item: ' . htmlspecialchars($item['name']) . ' | Quantity: ' . htmlspecialchars($item['quantity']) . '</div>';
        echo '<div class="item-price">Price: $' . htmlspecialchars(number_format($item['price'], 2)) . '</div>';
        echo '</div>';
        $total += $item['price'] * $item['quantity'];
    }
    ?>

    <div class="cart-total">Total: $<?php echo number_format($total, 2); ?></div>

    <!-- Form to place order -->
    <form action="place_order.php" method="POST">
        <input type="hidden" name="order_data" value="<?php echo htmlspecialchars(json_encode($cart_items)); ?>">
        <button type="submit" class="place-order-btn">Place Order</button>
    </form>
</div>

</body>
</html>
