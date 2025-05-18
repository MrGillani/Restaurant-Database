<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #1e1e2f;
        }

        .sidebar {
            width: 250px;
            background-color: #2c2f48;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }

        .sidebar .logo {
            color: #ffa95f;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            height: 100vh;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            z-index: 0;
        }

        h1 {
            color: #ffa95f;
            margin-bottom: 40px;
            font-size: 36px;
            position: relative;
            z-index: 1;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .menu-btn {
            background: linear-gradient(135deg, #ffa95f, #ff7e5f);
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #ffc371, #ff5f6d);
        }

        .menu-btn:active {
            transform: scale(0.97);
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
        <h1>Order Management</h1>
        <div class="button-container">
            <button class="menu-btn" onclick="location.href='take_order.php'">📝 Take Order</button>
            <button class="menu-btn" onclick="location.href='view_orders.php'">📂 View Open Orders</button>
            <button class="menu-btn" onclick="location.href='manage_orders.php'">⚙️ Manage Orders</button>
        </div>
    </div>
</body>
</html>
