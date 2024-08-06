<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT *
            FROM orders
            INNER JOIN cust_table ON orders.tableID = cust_table.tableID
            INNER JOIN menu_order ON orders.orderID = menu_order.orderID
            INNER JOIN menu_item ON menu_order.menuitemID = menu_item.menuitemID
            INNER JOIN category ON category.categoryID = menu_item.categoryID
            WHERE orders.orderStatusHot = 1 OR orders.orderStatusDrink = 1 OR orders.orderStatusDessert = 1 OR orders.orderStatusHot = 2 OR orders.orderStatusDrink = 2 OR orders.orderStatusDessert = 2
            ORDER BY orders.orderDateTime";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];
while ($row = mysqli_fetch_array($result)) {
    $tableNo = $row['table_num'];
    $orderID = $row['orderID'];
    if (!isset($arrContent[$tableNo])) {
        $arrContent[$tableNo] = [
            'table_num' => $tableNo,
            'orders' => []
        ];
    }
    if (!isset($arrContent[$tableNo]['orders'][$orderID])) {
        $arrContent[$tableNo]['orders'][$orderID] = [
            'orderDateTime' => $row['orderDateTime'],
            'items' => []
        ];
    }
    $arrContent[$tableNo]['orders'][$orderID]['items'][] = [
        'quantity' => $row['quantity'],
        'item_name' => $row['item_name'],
        'special_request' => $row['special_request'],
        'serve_later' => $row['serve_later'],
        'prepTime' => isset($row['prepTime']) ? $row['prepTime'] : 'N/A'
    ];
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dessert Orders Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 120px;
                justify-content: center;
                align-items: center;
            }
            .navbar {
                background-color: #343a40;
            }
            .navbar-brand {
                font-size: 40px;
            }
            .navbar-nav .nav-link {
                color: #ffffff;
            }
            .navbar-nav .nav-link:hover {
                color: #cccccc;
            }            
            .ordernav {
                margin-right: 10px;
            }                       
            .serve-later {
                color: blue;
            }
            .table-header h3 {
                margin: 0;
                margin-bottom: 5px;
            }
            .table-header .order-time {
                display: block;
                color: grey;
                font-size: 1rem;
                margin-bottom: 5px;
            }
            .table-header .order-id {
                margin: 0;
                font-size: 1rem;
            }            
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">View All Orders</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../../Login/admin.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Admin/tables/tables_admin.php">Tables</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Admin/user/viewUsers.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Admin/menu/viewMenu.php">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Admin/orders/viewAllOrders.php">View All Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Admin/reports/report.php">Reports</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-outline-success ordernav" href="../../Admin/orders/viewAllOrders.php">View All Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row mb-3">
                <div class="col-md-4"></div>
            </div>

            <div class="container">
                <?php
                foreach ($arrContent as $tableNo => $tableData) {
                    foreach ($tableData['orders'] as $orderID => $orderData) {
                        ?>
                        <div id="table-container-<?php echo $tableNo; ?>-<?php echo $orderID; ?>">
                            <div class="row align-items-center mb-2">
                                <div class="col-md-8 table-header">
                                    <h3>Table No. <?php echo $tableNo; ?></h3>
                                    <h5 class="order-id">Order ID: <?php echo $orderID; ?></h5>
                                    <span class="order-time"><?php echo $orderData['orderDateTime']; ?></span>
                                </div>
                            </div>
                            <table class="table" id="orderTable_<?php echo $tableNo; ?>_<?php echo $orderID; ?>">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Menu Item</th>
                                        <th>Special Request</th>
                                        <th>Serve Later</th>
                                        <th>Preparation Time (mins)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderData['items'] as $item) { ?>
                                        <tr>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td class="dessert-name"><?php echo $item['item_name']; ?></td>
                                            <td><?php echo $item['special_request']; ?></td>
                                            <td>
                                                <?php
                                                if ($item['serve_later'] == 1) {
                                                    echo '<span class="serve-later">Serve later</span>';
                                                } else {
                                                    echo '<span class="serve-later">-</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $item['prepTime']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                }
                if (empty($arrContent)) {
                    ?>
                    <p>No orders found.</p>
                    <?php
                }
                ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>   
        <script>
        function refreshPage() {
            window.location.reload();
        }
            setInterval(refreshPage, 30000); 
        </script>
    </body>
</html>
