<?php
session_start();
include '../backend/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT menu_item.name, menu_item.price, cart.quantity, cart.item_id 
                        FROM cart 
                        JOIN menu_item ON cart.item_id = menu_item.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;  // Initialize total price
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/checkout.css">
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <ul class="checkout-items">
                <?php while ($row = $result->fetch_assoc()): 
                    $item_total = $row['price'] * $row['quantity'];
                    $total_price += $item_total;
                ?>
                    <li>
                        <span class="item-name"><?php echo htmlspecialchars($row['name']); ?></span>
                        <div class="item-details">
                            <span class="item-price">$<?php echo number_format($row['price'], 2); ?></span> x 
                            <span class="item-quantity"><?php echo htmlspecialchars($row['quantity']); ?></span>
                            <span class="item-total">Total: $<?php echo number_format($item_total, 2); ?></span>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <div class="checkout-summary">
                <p><strong>Total Amount: $<?php echo number_format($total_price, 2); ?></strong></p>
                <form method="POST" action="process_payment.php">
                    <button type="submit" class="pay-now-btn">Pay Now</button>
                </form>
            </div>
        <?php else: ?>
            <p class="empty-cart-message">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
