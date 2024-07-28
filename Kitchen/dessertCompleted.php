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
        WHERE orders.orderStatusDessert = 1 AND stationID = 'STN3'
        ORDER BY menu_order.orderID ASC";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];
while ($row = mysqli_fetch_array($result)) {
    $tableNo = $row['table_num'];
    if (!isset($arrContent[$tableNo])) {
        $arrContent[$tableNo] = [
            'table_num' => $tableNo,
            'orderDateTime' => $row['orderDateTime'],
            'orders' => []
        ];
    }
    $arrContent[$tableNo]['orders'][] = [
        'quantity' => $row['quantity'],
        'item_name' => $row['item_name'],
        'special_request' => $row['special_request'],
        'serve_later' => $row['serve_later'],
        'prepTime' => $row['prepTime'],
        'orderID' => $row['orderID']
    ];
}
mysqli_close($link);
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Dessert Completed Orders Page</title>
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
        
        .backnav {
            position: absolute;
            left: 0%;
        }
            .completed-btn {
                position: relative;
                padding: 5px;
                bottom: -11px;
                left: 50%;
                transform: translateX(-50%);
            }
            .serve-later {
                color: blue;
            }
            .table-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .table-header h3 {
                margin: 0;
            }
            .table-header .order-time {
                font-size: 1.2rem;
                color: grey;
            }
        </style>
    </head>
    <body>
                            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container position-relative d-flex justify-content-center">
            <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="navbar-brand">Completed Orders - Dessert</div>
        </div>
    </nav>
        <div class="container">
            <!-- Navigation Buttons -->
            <div class="row mb-3">
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center">
                    <select class="form-control" id="categoryDropdown">
                        <option value="" disabled selected>-Choose Station-</option>
                        <option value="hot">Hot</option>
                        <option value="drinks">Drinks</option>
                        <option value="dessert">Dessert</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <a class="btn btn-danger" id="clearBtn" onclick="confirmClear()">Clear</a>
                </div>
            </div>
            <div class="container" id="allTablesContainer">
                <?php
                foreach ($arrContent as $tableNo => $tableData) {
                    ?>
                    <div class="table-container" id="table-container-<?php echo $tableNo; ?>">
                        <div class="row align-items-center mb-2">
                            <div class="col-md-8 table-header">
                                <h3>Table No. <?php echo $tableNo; ?></h3>
                                <span class="order-time"><?php echo $tableData['orderDateTime']; ?></span>
                            </div>
                        </div>
                        <table class="table" id="orderTable_<?php echo $tableNo; ?>">
                            <thead>
                                <tr>
                                    <th>Quantity</th>
                                    <th>Dessert</th>
                                    <th>Special Request</th>
                                    <th>Serve Later</th>
                                    <th>Preparation Time (mins)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableData['orders'] as $order) { ?>
                                    <tr data-order-id="<?php echo $order['orderID']; ?>">
                                        <td><?php echo $order['quantity']; ?></td>
                                        <td><?php echo $order['item_name']; ?></td>
                                        <td><?php echo $order['special_request']; ?></td>
                                        <td>
                                            <?php
                                            if ($order['serve_later'] == 1) {
                                                echo '<span class="serve-later">Serve later</span>';
                                            } else {
                                                echo '<span class="serve-later">-</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $order['prepTime']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php
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
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>

                        $(document).ready(function () {
                            $('#categoryDropdown').change(function () {
                                let selectedOption = $(this).val();
                                let redirectUrl = '';
                                switch (selectedOption) {
                                    case 'drinks':
                                        redirectUrl = '/FYP_FoodOrderApp/Kitchen/drinksCompleted.php';
                                        break;
                                    case 'hot':
                                        redirectUrl = '/FYP_FoodOrderApp/Kitchen/hotCompleted.php';
                                        break;
                                    case 'dessert':
                                        redirectUrl = '/FYP_FoodOrderApp/Kitchen/dessertCompleted.php';
                                        break;
                                    default:
                                        redirectUrl = '';
                                }

                                // Redirect to the selected page
                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                }
                            });
                        });

                        function goBack() {
                            history.back();
                        }

                        function confirmClear() {
                            if (confirm("Are you sure you want to clear all completed orders?")) {
                                $.post('/FYP_FoodOrderApp/Kitchen/clearOrders.php', {clear3: true}, function (response) {
                                    if (response.includes("success")) {
                                        // Clear all tables from the view
                                        $('#allTablesContainer').empty();
                                        // Show a message when all orders are cleared
                                        $('#allTablesContainer').html('<p class="text-center">All completed orders have been cleared.</p>');

                                        // Clear timers from localStorage
                                        clearTimersFromLocalStorage();
                                    } else {
                                        alert("Error clearing orders: " + response);
                                    }
                                });
                            }
                        }

                        function clearTimersFromLocalStorage() {
                            for (let i = 0; i < localStorage.length; i++) {
                                const key = localStorage.key(i);
                                if (key.startsWith('timer_')) {
                                    localStorage.removeItem(key);
                                }
                            }
                        }
                        
                            // Add event listener to the logout button
    document.getElementById('clearBtn').addEventListener('click', function (event) {
        if (!confirm('Are you sure you want to clear completed orders?')) {
            event.preventDefault();
        }
    });
                        
                                            function navigateToBack() {
            // Redirect to the next category page (replace URL with desired destination)
            window.location.href = "/FYP_FoodOrderApp/Kitchen/dessert.php"; // Change "kitchen.html" to the actual URL of the next page
        }
        
                    function refreshPage() {
                window.location.reload();
            }

            // Set the interval to refresh the page every 30 seconds (30000 milliseconds)
            setInterval(refreshPage, 30000); 
            </script>
    </body>
</html>
