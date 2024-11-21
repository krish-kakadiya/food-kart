<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Include the database connection
include 'connection.php';

// Get the count of items in the user's cart
$sql = "SELECT SUM(quantity) AS count FROM cart WHERE user_id = $user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Return the cart item count
echo json_encode(['count' => $row['count'] ? $row['count'] : 0]);
exit;
?>
