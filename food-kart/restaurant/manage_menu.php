<?php
session_start();
include '../backend/connection.php';

// Redirect to login if restaurant_id is not set
if (!isset($_SESSION['restaurant_id'])) {
    header("Location: ../backend/restaurant_login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];

// Validate that restaurant_id exists in the restaurant table
$validate_restaurant = $conn->prepare("SELECT id FROM restaurant WHERE id = ?");
$validate_restaurant->bind_param("i", $restaurant_id);
$validate_restaurant->execute();
$validate_restaurant->store_result();

if ($validate_restaurant->num_rows == 0) {
    echo "Error: Invalid restaurant ID.";
    exit();
}
$validate_restaurant->close();

// Handle form submission for adding a new menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../assets/images/' . $image_name;

        // Max file size (e.g., 2MB)
        $max_file_size = 2 * 1024 * 1024;  // 2MB

        // Check if file size is within the limit
        if ($_FILES['image']['size'] > $max_file_size) {
            $message = "File size exceeds the limit. Maximum size is 2MB.";
            exit();
        }

        // Validate file type (only allow images)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_extension, $allowed_extensions)) {
            // Move uploaded image to the images folder
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Insert menu item into the database
                $stmt = $conn->prepare("INSERT INTO menu_item (name, description, price, restaurant_id, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdss", $name, $description, $price, $restaurant_id, $image_name);
                if ($stmt->execute()) {
                    $message = "Menu item added successfully!";
                } else {
                    $message = "Failed to add menu item. Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $message = "Image upload error or no image selected.";
    }
}

// Fetch all menu items for the logged-in restaurant
$items = $conn->query("SELECT * FROM menu_item WHERE restaurant_id = $restaurant_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link rel="stylesheet" href="../assets/css/manage_menu.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Manage Your Menu</h1>
        <p>This is where you can add, update, and remove menu items.</p>

        <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>

        <!-- Form to Add New Menu Item -->
        <form method="POST" action="manage_menu.php" enctype="multipart/form-data" class="menu-form">
            <h2>Add New Item</h2>
            <label for="name">Item Name:</label>
            <input type="text" name="name" required>
            
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" required>
            
            <label for="image">Dish Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit" name="add_item">Add Item</button>
        </form>

        <!-- List of Menu Items with Options to Edit/Delete -->
        <h2>Your Menu Items</h2>
        <table class="menu-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 50px; height: 50px;">
                    </td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form action="update_menu_item.php" method="post" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <form action="delete_menu_item.php" method="post" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
