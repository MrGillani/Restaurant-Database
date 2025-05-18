<?php
require 'db.php';

// Default query
$query = "SELECT * FROM menu_items WHERE 1=1";
$params = [];

// Apply filters if submitted
if (!empty($_GET['min_price'])) {
    $query .= " AND price >= ?";
    $params[] = $_GET['min_price'];
}

if (!empty($_GET['max_price'])) {
    $query .= " AND price <= ?";
    $params[] = $_GET['max_price'];
}

if (!empty($_GET['category'])) {
    $query .= " AND category = ?";
    $params[] = $_GET['category'];
}

$query .= " ORDER BY id ASC"; // Sort by ID ascending

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$menuItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            background-color: #1e1e2f;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        .sidebar {
            width: 250px;
            background-color: #2c2f48;
            position: fixed;
            height: 100%;
            padding: 20px;
        }

        .sidebar .logo {
            color: #ffa95f;
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
        }

        .main-content {
            margin-left: 270px;
            padding: 30px;
        }

        h1 {
            color: #ffa95f;
            margin-bottom: 20px;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #2e2e3e;
            border-radius: 12px;
            padding: 15px;
            width: 250px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
        }

        .card h3 {
            margin: 10px 0 5px;
            color: #ffa95f;
        }

        .card p {
            margin: 3px 0;
            font-size: 14px;
        }

        .filter-form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #2c2f48;
            border-radius: 10px;
        }

        .filter-form label {
            margin-right: 10px;
        }

        .filter-form input,
        .filter-form select {
            padding: 8px 10px;
            border-radius: 6px;
            border: none;
            margin-right: 15px;
            background-color: #444459;
            color: white;
        }

        .filter-form button {
            padding: 10px 20px;
            background-color: #ffa95f;
            color: #1e1e2f;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .filter-form button:hover {
            background-color: #ff8c42;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2 class="logo">🍽️ RestroPanel</h2>
    <a href="index.php" style="color: #ffa95f; text-decoration: none; display: block; margin: 15px 0;">← Dashboard</a>

    <div style="margin-top: 30px;">
        <a href="menu.php" style="text-decoration: none;">
        <h3 style="color: #ffa95f; font-size: 16px;">Menu Management</h3>
        </a>

    </div>

    <div style="margin-top: 30px;">
    <a href="order_manage.php" style="text-decoration: none;">
    <h3 style="color: #ffa95f; font-size: 16px;">Order Management</h3>
    </a>
    </div>
</div>

<div class="main-content">
    <h1>🍴 Menu Items</h1>

    <form method="GET" class="filter-form">
        <label for="min_price">Min Price:</label>
        <input type="number" name="min_price" id="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">

        <label for="max_price">Max Price:</label>
        <input type="number" name="max_price" id="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">

        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="">All</option>
            <option value="Starters" <?= (($_GET['category'] ?? '') === 'Starters') ? 'selected' : '' ?>>Starters</option>
            <option value="Main Course" <?= (($_GET['category'] ?? '') === 'Main Course') ? 'selected' : '' ?>>Main Course</option>
            <option value="Desserts" <?= (($_GET['category'] ?? '') === 'Desserts') ? 'selected' : '' ?>>Desserts</option>
            <option value="Drinks" <?= (($_GET['category'] ?? '') === 'Drinks') ? 'selected' : '' ?>>Drinks</option>
        </select>

        <button type="submit">Apply Filters</button>
    </form>

    <div class="gallery">
        <?php foreach ($menuItems as $item): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($item['picture']); ?>" alt="Menu Image">
                <h3><?= htmlspecialchars($item['name']); ?></h3>
                <p><strong>ID:</strong> <?= $item['id']; ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($item['category']); ?></p>
                <p><strong>Price:</strong> Rs. <?= htmlspecialchars($item['price']); ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($item['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
