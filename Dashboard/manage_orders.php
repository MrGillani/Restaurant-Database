<?php
require 'db.php';

// Handle filter
$statusFilter = $_GET['status'] ?? 'All';

if ($statusFilter === 'All') {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_time DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE status = :status ORDER BY order_time DESC");
    $stmt->execute(['status' => $statusFilter]);
}
$orders = $stmt->fetchAll();

// Fetch item names
$itemStmt = $pdo->query("SELECT id, name FROM menu_items");
$itemMap = [];
while ($row = $itemStmt->fetch()) {
    $itemMap[$row['id']] = $row['name'];
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $update = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $update->execute([
        'status' => $_POST['new_status'],
        'id' => $_POST['order_id']
    ]);
    header("Location: manage_orders.php?status=" . urlencode($statusFilter));
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
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
            width: 850px;
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

        .filter-form {
            text-align: center;
            margin-bottom: 20px;
        }

        select, button {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            margin: 0 5px;
        }

        .order-card {
            background-color: #383850;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .order-header {
            font-weight: bold;
            color: #ffa95f;
            padding: 15px;
            cursor: pointer;
        }

        .order-details {
            display: none;
            padding: 0 15px 15px 15px;
            font-size: 14px;
        }

        .order-details ul {
            list-style: none;
            padding-left: 0;
        }

        .order-details li {
            margin-bottom: 5px;
        }

        .order-card:hover .order-header {
            background-color: #4a4a68;
        }

        form.update-form {
            margin-top: 10px;
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
            details.style.display = (details.style.display === "block") ? "none" : "block";
        }
    </script>
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
        <div class="container">
            <h1>🛠️ Manage Orders</h1>

            <form method="GET" class="filter-form">
                <label for="status">Filter by Status:</label>
                <select name="status" onchange="this.form.submit()">
                    <option <?= $statusFilter === 'All' ? 'selected' : '' ?>>All</option>
                    <option <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option <?= $statusFilter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </form>

            <?php if (count($orders) === 0): ?>
                <p style="text-align: center; color: #ccc;">No orders found for this status.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header" onclick="toggleDetails(<?= $order['id']; ?>)">
                            Order #<?= htmlspecialchars($order['order_id']); ?> — <?= date('d M Y, H:i', strtotime($order['order_time'])); ?>
                        </div>
                        <div class="order-details" id="details-<?= $order['id']; ?>">
                            <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?> (<?= htmlspecialchars($order['customer_contact']); ?>)</p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
                            <p><strong>Items:</strong></p>
                            <ul>
                                <?php
                                $items = json_decode($order['items'], true);
                                foreach ($items as $itemId => $qty) {
                                    $itemName = $itemMap[$itemId] ?? 'Unknown';
                                    echo "<li>{$itemName} × {$qty}</li>";
                                }
                                ?>
                            </ul>
                            <p><strong>Total:</strong> Rs. <?= htmlspecialchars($order['total']); ?></p>

                            <form method="POST" class="update-form">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <label>Update Status:</label>
                                <select name="new_status">
                                    <option <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
