<?php
require 'db.php';

// Fetch active orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE status IN ('Pending', 'Preparing') ORDER BY order_time DESC");
$stmt->execute();
$activeOrders = $stmt->fetchAll();

// Fetch item names for mapping
$itemStmt = $pdo->query("SELECT id, name FROM menu_items");
$itemMap = [];
while ($row = $itemStmt->fetch()) {
    $itemMap[$row['id']] = $row['name'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Active Orders</title>
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
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            width: 800px;
            background-color: #2e2e3e;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #ffa95f;
            margin-bottom: 20px;
        }

        .order-card {
            background-color: #383850;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .order-header {
            font-weight: bold;
            color: #ffa95f;
        }

        .order-details {
            display: none;
            margin-top: 10px;
            font-size: 14px;
        }

        .order-details ul {
            list-style: none;
            padding-left: 0;
        }

        .order-details li {
            margin-bottom: 5px;
        }

        .order-card:hover {
            background-color: #4a4a68;
        }

        a {
            color: #ffa95f;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
    <script>
        function toggleDetails(id) {
            const details = document.getElementById("details-" + id);
            details.style.display = (details.style.display === "none") ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2 class="logo">🍽️ RestroPanel</h2>
        <a href="index.php" style="color: #ffa95f; text-decoration: none; display: block; margin: 15px 0;">← Dashboard</a>
        <h3 style="color: #ffa95f; font-size: 16px; margin-top: 30px;"><a href="menu.php" style="color: #ffa95f; text-decoration: none;">Menu Management</a></h3>
        <h3 style="color: #ffa95f; font-size: 16px; margin-top: 30px;"><a href="order_manage.php" style="color: #ffa95f; text-decoration: none;">Order Management</a></h3>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>📦 Active Orders</h1>

            <?php if (count($activeOrders) === 0): ?>
                <p style="text-align: center; color: #ccc;">No active orders at the moment.</p>
            <?php else: ?>
                <?php foreach ($activeOrders as $order): ?>
                    <div class="order-card" onclick="toggleDetails(<?= $order['id']; ?>)">
                        <div class="order-header">Order #<?= htmlspecialchars($order['order_id']); ?> — <?= date('d M Y, H:i', strtotime($order['order_time'])); ?></div>
                        <div class="order-details" id="details-<?= $order['id']; ?>">
                            <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?> (<?= htmlspecialchars($order['customer_contact']); ?>)</p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
                            <p><strong>Items Ordered:</strong></p>
                            <ul>
                                <?php
                                $items = json_decode($order['items'], true);
                                foreach ($items as $itemId => $qty) {
                                    $itemName = $itemMap[$itemId] ?? 'Unknown Item';
                                    echo "<li>{$itemName} × {$qty}</li>";
                                }
                                ?>
                            </ul>
                            <p><strong>Total:</strong> Rs. <?= htmlspecialchars($order['total']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
