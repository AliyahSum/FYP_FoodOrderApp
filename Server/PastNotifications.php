<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$readyOrdersQuery = "SELECT *
                         FROM menu_order mo
                         INNER JOIN orders o ON o.orderID = mo.orderID
                         INNER JOIN menu_item mi ON mi.menuitemID = mo.menuitemID
                         INNER JOIN category c ON c.categoryID = mi.categoryID
                         INNER JOIN cust_table ct ON ct.tableID = o.tableID
                         WHERE mo.notif = 2
                         ORDER BY o.orderDateTime DESC";

$readyOrdersResult = mysqli_query($link, $readyOrdersQuery) or die(mysqli_error($link));

function fetchOrders($result) {
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orderId = $row['orderID'];
        if (!isset($orders[$orderId])) {
            $orders[$orderId] = [
                'table_num' => $row['table_num'],
                'orderDateTime' => $row['orderDateTime'],
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
        <title>Past Notifications</title>
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
            }            
            .header {
                color: #ffffff;
                padding: 20px 0;
                text-align: center;
                font-size: 40px;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .container-fluid {
                max-width: 1340px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .notification {
                border: 1px solid #ccc;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            .notification-title {
                font-weight: bold;
            }
            .notification-timestamp {
                color: #666;
                font-size: 0.9em;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Notifications</a>
                <div class="justify-content-between" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="Notifications.php" class="btn btn-outline-secondary ms-auto" role="button" aria-label="Return to Notifications">
                                Return to Notifications
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <h1 class="text-center">Past Notifications</h1>
            <br>
            <?php foreach ($readyOrders as $orderId => $order): ?>
                <div class="notification">
                    <div class="notification-title">Table No. <?php echo htmlspecialchars($order['table_num']); ?> (Order ID: <?php echo htmlspecialchars($orderId); ?>)</div>
                    <div class="notification-timestamp"><?php echo htmlspecialchars($order['orderDateTime']); ?></div>
                    <?php foreach ($order['menu_items'] as $item): ?>
                        <div class="notification-body"><?php echo htmlspecialchars($item['item_name']); ?> has been served.</div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
