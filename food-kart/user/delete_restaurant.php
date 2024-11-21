<?php
session_start();
include '../backend/connection.php';

// Check if the restaurant owner is logged in by verifying `restaurant_id` in the session
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: ../backend/restaurant_login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];

// SQL to delete the restaurant
$delete_sql = "DELETE FROM restaurant WHERE id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $restaurant_id);

if ($stmt->execute()) {
    // Clear session and redirect to login page with a success message
    session_unset();
    session_destroy();
    header("Location: ../backend/restaurant_login.php?message=Restaurant successfully deleted.");
} else {
    echo "Error deleting restaurant.";
}
$stmt->close();
$conn->close();
exit();
?>
