<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $updateOrderQuery = "UPDATE menu_order SET notif = 2 WHERE orderID = $orderId";
    mysqli_query($link, $updateOrderQuery) or die(mysqli_error($link));
    echo json_encode(['success' => true]);
    exit;
}

$preparingOrdersQuery = "SELECT *
                         FROM menu_order mo
                         INNER JOIN orders o ON o.orderID = mo.orderID
                         INNER JOIN menu_item mi ON mi.menuitemID = mo.menuitemID
                         INNER JOIN category c ON c.categoryID = mi.categoryID
                         INNER JOIN cust_table ct ON ct.tableID = o.tableID
                         WHERE mo.notif = 0
                         ORDER BY o.orderID";

$preparingOrdersResult = mysqli_query($link, $preparingOrdersQuery) or die(mysqli_error($link));

$readyOrdersQuery = "SELECT *
                         FROM menu_order mo
                         INNER JOIN orders o ON o.orderID = mo.orderID
                         INNER JOIN menu_item mi ON mi.menuitemID = mo.menuitemID
                         INNER JOIN category c ON c.categoryID = mi.categoryID
                         INNER JOIN cust_table ct ON ct.tableID = o.tableID
                         WHERE mo.notif = 1
                         ORDER BY o.orderID";

$readyOrdersResult = mysqli_query($link, $readyOrdersQuery) or die(mysqli_error($link));

function fetchOrders($result) {
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orderId = $row['orderID'];
        if (!isset($orders[$orderId])) {
            $orders[$orderId] = [
                'table_num' => $row['table_num'],
                'menu_items' => []
            ];
        }
        $orders[$orderId]['menu_items'][] = [
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity'],
            'category' => $row['category_name'],
            'item_option' => $row['item_option'],
            'special_request' => $row['special_request']
        ];
    }
    return $orders;
}

$preparingOrders = fetchOrders($preparingOrdersResult);
$readyOrders = fetchOrders($readyOrdersResult);

function getItemOptionWord($category, $itemOption) {
    if ($category === 'Lunch Menu') {
        switch ($itemOption) {
            case '1':
                return '';
            case '2':
                return 'Rare';
            case '3':
                return 'Medium Rare';
            case '4':
                return 'Medium';
            case '5':
                return 'Medium Well';
            case '6':
                return 'Well Done';
            default:
                return 'NA';
        }
    } elseif ($category === 'Drinks') {
        switch ($itemOption) {
            case '1':
                return '';
            case '2':
                return 'Hot';
            case '3':
                return 'Cold';
            default:
                return 'NA';
        }
    }
    return 'NA';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notification Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                padding-top: 80px;
                padding-bottom: 100px;
                min-height: 100vh;
                font-size: 16px;
            }
            .navbar {
                background-color: #343a40;
            }
            .navbar-brand {
                font-size: 40px;
                margin-left: 25px;
            }
            .backnav {
                margin-left:40px;
            }
            .btn-outline-light {
                border-color: #ffffff;
                color: #ffffff;
            }
            .btn-outline-light:hover {
                background-color: #ffffff;
                color: #343a40;
            }            
            .container-fluid {
                max-width: 1340px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .orders-section {
                padding: 20px;
            }
            .orders-section h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            .orders-list {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            .order-card {
                width: 250px;
                margin: 15px;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s;
            }
            .order-card:hover {
                transform: translateY(-5px);
            }
            .order-card p {
                margin: 0;
            }
            .order-card .item-option {
                color: blue;
                margin: 5px 0;
            }
            .order-card .special-request {
                color: red;
                margin: 5px 0;
            }
            .order-card .btn-served {
                margin-top: 10px;
                display: block;
                width: 100%;
            }
            h2{
                background-color:black;
                color:white;
                padding:20px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <a class="navbar-brand" href="#">Notifications</a>
                <a href="PastNotifications.php" class="btn btn-outline-success ms-auto">
                    View Past Notifications
                </a>
            </div>
        </nav>

        <div class="container">
            <div class="orders-section">
                <h2>Ready</h2>
                <div class="orders-list" id="ready-orders">
                    <?php
                    foreach ($readyOrders as $orderId => $order) {
                        echo "<div class='order-card'>";
                        echo "<h3><strong>Table:</strong> {$order['table_num']}</h3>";
                        echo "<p><strong>Order ID:</strong> {$orderId}</p>";
                        foreach ($order['menu_items'] as $menuItem) {
                            echo "<p>{$menuItem['quantity']} x {$menuItem['item_name']}</p>";
                            if (!empty($menuItem['item_option'])) {
                                $itemOptionWord = getItemOptionWord($menuItem['category'], $menuItem['item_option']);
                                echo "<p class='item-option'>{$itemOptionWord}</p>";
                            }
                            if (!empty($menuItem['special_request'])) {
                                echo "<p class='special-request'>{$menuItem['special_request']}</p>";
                            }
                        }
                        echo "<button class='btn btn-success btn-served' data-order-id='{$orderId}'>Served</button>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="orders-section">
                <h2>Preparing</h2>
                <div class="orders-list" id="preparing-orders">
                    <?php
                    foreach ($preparingOrders as $orderId => $order) {
                        echo "<div class='order-card'>";
                        echo "<h3><strong>Table:</strong> {$order['table_num']}</h3>";
                        echo "<p><strong>Order ID:</strong> {$orderId}</p>";
                        foreach ($order['menu_items'] as $menuItem) {
                            echo "<p>{$menuItem['quantity']} x {$menuItem['item_name']}</p>";
                            if (!empty($menuItem['item_option'])) {
                                $itemOptionWord = getItemOptionWord($menuItem['category'], $menuItem['item_option']);
                                echo "<p class='item-option'>{$itemOptionWord}</p>";
                            }
                            if (!empty($menuItem['special_request'])) {
                                echo "<p class='special-request'>{$menuItem['special_request']}</p>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.querySelectorAll('.btn-served').forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm('Are you sure you served all the food?')) {
                        const orderId = this.getAttribute('data-order-id');
                        fetch('', {method: 'POST',headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `order_id=${orderId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('.order-card').remove();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });            
            function refreshPage() {
                window.location.reload();
            }
            setInterval(refreshPage, 10000);
            function navigateToBack() {
                window.location.href = "../Server/OrderMenu.php";
            }
        </script>
    </body>
</html>