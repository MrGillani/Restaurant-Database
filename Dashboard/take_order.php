<?php
require 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $order_time = $_POST['order_time'];
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $quantities = $_POST['quantities'] ?? [];

    // Filter out items with quantity > 0
    $selected_items = array_filter($quantities, function ($qty) {
        return intval($qty) > 0;
    });

    if (empty($selected_items)) {
        $error = "Please select at least one item with quantity.";
    } else {
        // Calculate total
        $net_total = 0;
        foreach ($selected_items as $item_id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM menu_items WHERE id = ?");
            $stmt->execute([$item_id]);
            $price = $stmt->fetchColumn();
            $net_total += $price * $qty;
        }

        // Insert into orders
        $items_json = json_encode($selected_items); // JSON encoded for storing multiple items
        $status = 'Pending';

        $stmt = $pdo->prepare("INSERT INTO orders (order_id, order_time, customer_name, customer_contact, items, total, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $order_time, $customer_name, $customer_contact, $items_json, $net_total, $status]);

        $success = "Order placed successfully!";
    }
}

// Fetch menu items
$query = "SELECT * FROM menu_items ORDER BY id ASC";
$stmt = $pdo->query($query);
$menuItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Take Order</title>
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
        .form-container {
            background-color: #2e2e3e;
            padding: 30px;
            border-radius: 12px;
            width: 600px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #ffa95f;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #ffa95f;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 15px;
            background-color: #44445a;
            color: white;
        }
        .menu-section {
            max-height: 300px;
            overflow-y: auto;
            background-color: #383850;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            background-color: #2e2e3e;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .item-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 10px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .item-price {
            font-size: 14px;
            color: #aaa;
        }
        .qty-box {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }
        .qty-input {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: none;
            background-color: #44445a;
            color: white;
        }
        input[type="number"]::-webkit-inner-spin-button {
            opacity: 1;
        }
        button {
            background: linear-gradient(135deg, #ffa95f, #ff7e5f);
            border: none;
            padding: 12px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(135deg, #ffc371, #ff5f6d);
        }
        #net-total {
            text-align: right;
            font-size: 18px;
            color: #ffa95f;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: lightgreen;
        }
        .error {
            color: tomato;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2 class="logo">🍽️ RestroPanel</h2>
    <a href="index.php" style="color: #ffa95f; text-decoration: none; display: block; margin: 15px 0;">← Dashboard</a>
    <div style="margin-top: 30px;">
        <h3 style="color: #ffa95f; font-size: 16px;">
            <a href="menu.php" style="color: #ffa95f; text-decoration: none;">Menu Management</a>
        </h3>
    </div>
    <div style="margin-top: 30px;">
        <a href="order_manage.php" style="text-decoration: none;">
            <h3 style="color: #ffa95f; font-size: 16px;">Order Management</h3>
        </a>
    </div>
</div>

<div class="main-content">
    <div class="form-container">
        <h1>📝 Take Order</h1>

        <?php if (isset($success)): ?>
            <div class="message"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="take_order.php" id="orderForm">
            <label>Order ID:</label>
            <input type="text" name="order_id" required>

            <label>Order Date & Time:</label>
            <input type="datetime-local" name="order_time" required>

            <label>Customer Name:</label>
            <input type="text" name="customer_name" required>

            <label>Customer Contact:</label>
            <input type="text" name="customer_contact" required>

            <label>Select Items:</label>
            <div class="menu-section">
                <?php foreach ($menuItems as $item): ?>
                    <div class="menu-item">
                        <img src="uploads/<?php echo $item['id']; ?>.jpeg" alt="Item"
                             onerror="this.onerror=null; this.src='uploads/default.png';"
                             class="item-img">
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="item-price" data-price="<?php echo $item['price']; ?>">Rs. <?php echo number_format($item['price']); ?></div>
                        </div>
                        <div class="qty-box">
                            <input class="qty-input" type="number" name="quantities[<?php echo $item['id']; ?>]" min="0" value="0" data-id="<?php echo $item['id']; ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="net-total">Net Total: Rs. 0</div>

            <button type="submit">Place Order</button>
        </form>
    </div>
</div>

<script>
    const priceMap = {
        <?php foreach ($menuItems as $item): ?>
        "<?php echo $item['id']; ?>": <?php echo $item['price']; ?>,
        <?php endforeach; ?>
    };

    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalDisplay = document.getElementById('net-total');

    function calculateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            const id = input.dataset.id;
            const qty = parseInt(input.value) || 0;
            total += qty * priceMap[id];
        });
        totalDisplay.textContent = "Net Total: Rs. " + total;
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    window.onload = calculateTotal;
</script>
</body>
</html>
