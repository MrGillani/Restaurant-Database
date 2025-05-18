<?php
// Database connection
$host = "localhost";
$port = "5432";
$dbname = "restaurant_db";
$user = "postgres";
$password = "postgres";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database connection failed.");
}

// Fetch stats
$totalOrdersQuery = "SELECT COUNT(*) FROM orders";
$pendingOrdersQuery = "SELECT COUNT(*) FROM orders WHERE status = 'pending'";
$menuItemsQuery = "SELECT COUNT(*) FROM menu_items";

$totalOrders = pg_fetch_result(pg_query($conn, $totalOrdersQuery), 0, 0);
$pendingOrders = pg_fetch_result(pg_query($conn, $pendingOrdersQuery), 0, 0);
$menuItems = pg_fetch_result(pg_query($conn, $menuItemsQuery), 0, 0);

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #1f1f2f;
            color: #fff;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2e2e3e;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .logo {
            color: #ffa95f;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu > li {
            padding: 15px;
            margin: 10px 0;
            background-color: #3b3b4d;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            user-select: none;
        }

        .menu > li:hover {
            background-color: #ffa95f;
            color: #1f1f2f;
        }

        .submenu {
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
            display: none;
        }

        .submenu li {
            background-color: #4a4a5a;
            padding: 10px;
            margin: 5px 0;
            border-radius: 6px;
            transition: transform 0.3s ease;
            user-select: none;
        }

        .submenu li a {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .submenu li:hover {
            background-color: #ffa95f;
            color: #1f1f2f;
        }

        .show {
            display: block !important;
        }

        .main-content {
            flex: 1;
            position: relative;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .top-bar {
            position: relative;
            z-index: 1;
            max-width: 600px;
        }

        .top-bar h1 {
            font-size: 32px;
            color: #ffa95f;
            margin-bottom: 10px;
        }

        .top-bar p {
            color: #ccc;
            margin-bottom: 30px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-box {
            background-color: #3b3b4d;
            padding: 20px;
            border-radius: 12px;
            min-width: 150px;
            flex: 1;
            max-width: 200px;
            transition: 0.3s;
        }

        .stat-box:hover {
            background-color: #ffa95f;
            color: #1f1f2f;
        }

        .stat-box a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .stat-label {
            font-size: 14px;
            color: #ccc;
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("dashboard-submenu");
            dropdown.classList.toggle("show");
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2 class="logo">🍽️ Pizzious</h2>
        <ul class="menu">
            <li onclick="toggleDropdown()" tabindex="-1">Dashboard</li>
            <ul id="dashboard-submenu" class="submenu">
                <li tabindex="-1"><a href="menu.php">Menu Management</a></li>
                <li tabindex="-1"><a href="order_manage.php">Order Management</a></li>
            </ul>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1>Welcome To Pizzious</h1>
            <p>Love At First Bite</p>
            <div class="stats">
                <div class="stat-box">
                    <a href="manage_orders.php">
                        <div class="stat-number"><?= $totalOrders ?></div>
                        <div class="stat-label">Total Orders</div>
                    </a>
                </div>
                <div class="stat-box">
                    <a href="view_orders.php">
                        <div class="stat-number"><?= $pendingOrders ?></div>
                        <div class="stat-label">Pending Orders</div>
                    </a>
                </div>
                <div class="stat-box">
                    <a href="view_menu.php">
                        <div class="stat-number"><?= $menuItems ?></div>
                        <div class="stat-label">Menu Items</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
