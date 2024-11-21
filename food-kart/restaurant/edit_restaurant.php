<?php
session_start();
include '../backend/connection.php';

// Check if the restaurant owner is logged in by verifying `restaurant_id` in the session
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: ../backend/restaurant_login.php");
    exit();
}

// Fetch current restaurant data (name, email, and address) from the database
$restaurant_id = $_SESSION['restaurant_id'];
$stmt = $conn->prepare("SELECT name, email, address FROM restaurant WHERE id = ?");
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();

// Check if the form is submitted to update the restaurant details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Update the restaurant's details in the database
    $update_stmt = $conn->prepare("UPDATE restaurant SET name = ?, email = ?, address = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $name, $email, $address, $restaurant_id);

    if ($update_stmt->execute()) {
        // Redirect to dashboard after successful update
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating restaurant: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
    <link rel="stylesheet" href="../assets/css/edit_restaurant.css">

</head>
<body>
    <div class="form-container">
        <h2>Edit Restaurant Details</h2>
        <form method="POST" action="">
            <!-- Restaurant Name -->
            <label for="name">Restaurant Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>

            <!-- Restaurant Email -->
            <label for="email">Restaurant Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($restaurant['email']); ?>" required>

            <!-- Restaurant Address -->
            <label for="address">Restaurant Address:</label>
            <textarea name="address" rows="4" required><?php echo htmlspecialchars($restaurant['address']); ?></textarea>

            <!-- Submit Button -->
            <input type="submit" value="Update Details">
        </form>
    </div>
</body>
</html>
