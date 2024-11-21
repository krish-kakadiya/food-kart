<?php
session_start();
include '../backend/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

// You would process the payment here with a real payment gateway like Stripe or PayPal.
// For now, let's simulate a successful payment.

$user_id = $_SESSION['user_id'];
// Clear the cart after successful payment
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect to the order confirmation page
header("Location: order_confirmation.php");
exit();
?>
