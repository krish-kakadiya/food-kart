<?php
// profile.php
include '../backend/connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User ID is not set. Please log in.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Update user details
    $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_username, $new_email, $user_id);
    if ($update_stmt->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>User Profile</h1>
    <form method="POST" action="profile.php">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <input type="submit" value="Update Profile">
    </form>

    <h2>Your Orders</h2>
    <div class="orders">
        <?php
        // Fetch user orders
        $orders_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $orders_stmt->bind_param("i", $user_id);
        $orders_stmt->execute();
        $orders = $orders_stmt->get_result();

        while ($order = $orders->fetch_assoc()) { ?>
            <div class="order-card">
                <p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
                <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                <p>Total Price: <?php echo htmlspecialchars($order['total_price']); ?></p>
            </div>
        <?php } ?>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
