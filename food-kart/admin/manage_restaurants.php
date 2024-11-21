<?php
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=food_kart', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all restaurants
    $stmt = $pdo->query("SELECT * FROM restaurant");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Restaurants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td a {
            color: #28a745;
            font-size: 14px;
            margin-right: 10px;
        }

        table td a:hover {
            color: #218838;
        }

        .back-link {
            font-size: 18px;
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            border-bottom: 1px solid #007bff;
        }

        .back-link:hover {
            text-decoration: none;
            color: #0056b3;
        }

        /* Add card styles */
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-content {
            display: flex;
            justify-content: space-between;
        }

        .card-content div {
            flex: 1;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Manage Restaurants</h2>
        <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

        <div class="card">
            <div class="card-header">
                Restaurant List
            </div>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['username']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['email']); ?></td>
                            <td>
                                <a href="edit_restaurant.php?id=<?php echo $restaurant['id']; ?>">Edit</a> | 
                                <a href="delete_restaurant.php?id=<?php echo $restaurant['id']; ?>">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
