<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

include '../backend/connection.php';  // Include the database connection

// Check if the menu item ID is set
if (isset($_POST['menu_item_id'])) {
    $menu_item_id = $_POST['menu_item_id'];

    // Fetch the menu item from the database
    $stmt = $conn->prepare("SELECT * FROM menu_item WHERE id = ?");
    $stmt->bind_param("i", $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menu_item = $result->fetch_assoc();

        // Check if the cart session variable exists, if not, initialize it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the item already exists in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $menu_item['id']) {
                // If item exists, update the quantity
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // If item doesn't exist in cart, add it
        if (!$found) {
            $cart_item = [
                'id' => $menu_item['id'],
                'name' => $menu_item['name'],
                'price' => $menu_item['price'],
                'quantity' => 1,
                'restaurant_id' => $menu_item['restaurant_id'],
            ];
            $_SESSION['cart'][] = $cart_item;
        }

        echo "Item added to cart!";
    } else {
        echo "Item not found.";
    }
}

$stmt->close();
$conn->close();
?>
