<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $query = "DELETE FROM menu_items WHERE id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    header("Location: view_menu.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Menu Item</title>
    <style>
        body {
            background-color: #1e1e2f;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #2c2f48;
            position: fixed;
            height: 100%;
            padding: 20px;
        }

        .main-content {
            margin-left: 270px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: #2e2e3e;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
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

        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 15px;
            background-color: #44445a;
            color: white;
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
    <a href="order_manage.php" style="text-decoration: none;">
    <h3 style="color: #ffa95f; font-size: 16px;">Order Management</h3>
    </a>

        </a>
    </div>


</div>


    <div class="main-content">
        <div class="form-container">
            <h1>🗑️ Delete Item</h1>
            <form method="POST">
                <label>ID (to Delete):</label><input type="text" name="id" required>
                <button type="submit">Delete Item</button>
            </form>
        </div>
    </div>
</body>
</html>
