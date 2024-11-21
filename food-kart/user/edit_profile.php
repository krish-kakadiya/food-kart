<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit;
}

include '../backend/connection.php';  // Include the database connection

// Fetch the user information based on the user ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted form data
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // Update the user profile in the database (excluding the name field)
    $update_stmt = $conn->prepare("UPDATE user SET email = ?, username = ?, address = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $email, $username, $address, $user_id);

    if ($update_stmt->execute()) {
        // Redirect after successful update
        header("Location: dashboard.php");  // Redirect to the dashboard or wherever you want
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Food Kart</title>
    <link rel="stylesheet" href="../assets/css/edit_profile.css">
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        
        <!-- Profile Edit Form -->
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" required>

            <label for="address">Address:</label>
            <textarea name="address" rows="4" required><?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?></textarea>

            <input type="submit" value="Update Profile">
        </form>
        
        <a href="dashboard.php">Go back to Dashboard</a>
    </div>
</body>
</html>
