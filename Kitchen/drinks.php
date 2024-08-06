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
          WHERE orders.orderStatusDrink = 0 AND stationID = 'STN1'
          ORDER BY orders.orderDateTime";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];
while ($row = mysqli_fetch_array($result)) {
    $tableNo = $row['table_num'];
    $orderID = $row['orderID'];
    if (!isset($arrContent[$tableNo])) {
        $arrContent[$tableNo] = [
            'table_num' => $tableNo,
            'orderDateTime' => $row['orderDateTime'],
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
        'prepTime' => isset($row['prepTime']) ? $row['prepTime'] : 'N/A',
        'orderID' => $row['orderID']
    ];
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Drink Orders Page</title>
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
            .backnav {
                position: absolute;
                left: 0%;
            }
            .completed-btn {
                position: relative;
                padding: 5px;
                bottom: -11px;
                left: -10%;
            }
            .cancel-text {
                text-decoration: line-through;
            }
            .order-gone {
                display: none;
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
            .timer-cell {
                font-weight: bold;
            }
            .row-green {
                background-color: #d4edda;
            }
            .row-yellow {
                background-color: #fff3cd;
            }
            .row-red {
                background-color: #f8d7da;
            }
            audio{
                opacity: 0%;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container position-relative d-flex justify-content-center">
                <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <div class="navbar-brand">Kitchen Display System - Drink</div>
            </div>
        </nav>

        <div class="container">
            <div id="newOrderAlert" class="alert alert-success" role="alert" style="display:none;">
                New orders have arrived!
            </div>
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
                    <a href="../Kitchen/drinksCompleted.php" class="btn btn-success">View Completed Orders</a>
                </div>
            </div>
            <div id="ordersContent"></div>
            <audio id="newOrderAudio" src="../Audio/bell.mp3">
                Your browser does not support the audio element
            </audio>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                let currentOrderCount = sessionStorage.getItem('currentOrderCount') ? parseInt(sessionStorage.getItem('currentOrderCount')) : 0;
                const newOrderAudio = document.getElementById('newOrderAudio');
                function refreshContent() {
                    $.ajax({
                        url: 'getOrdersDrinks.php',
                        type: 'GET',
                        success: function (data) {
                            $('#ordersContent').html(data);
                            initializeTimers();
                            initializeEventListeners();
                            restoreCancelTextState();
                            
                            const newOrderCount = $('#ordersContent').find('tr[data-order-id]').length;
                            if (newOrderCount > currentOrderCount) {
                                $('#newOrderAlert').show().delay(5000).fadeOut();
                                newOrderAudio.play();
                            }
                            currentOrderCount = newOrderCount;
                            sessionStorage.setItem('currentOrderCount', currentOrderCount);
                        },
                        error: function () {
                            console.error('Error refreshing content');
                        }
                    });
                }
                function initializeTimers() {
                    $('tr[data-prep-time]').each(function () {
                        const $row = $(this);
                        const orderId = $row.data('menuorder-id');
                        const prepTime = parseInt($row.data('prep-time'), 10);
                        const totalTime = prepTime * 60;
                        
                        let remainingTime = localStorage.getItem(`timer_${orderId}`);
                        if (remainingTime === null) {
                            remainingTime = totalTime;
                        } else {
                            remainingTime = parseInt(remainingTime, 10);
                        }
                        
                        function updateTimer() {
                            const minutes = Math.floor(remainingTime / 60);
                            const seconds = remainingTime % 60;
                            const percentage = (remainingTime / totalTime) * 100;

                            $row.find('.timer-cell').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
                            
                            if (percentage > 50) {
                                $row.removeClass('row-yellow row-red').addClass('row-green');
                            } else if (percentage > 20) {
                                $row.removeClass('row-green row-red').addClass('row-yellow');
                            } else {
                                $row.removeClass('row-green row-yellow').addClass('row-red');
                            }
                            
                            if (remainingTime > 0) {
                                remainingTime--;
                                localStorage.setItem(`timer_${orderId}`, remainingTime);
                                setTimeout(updateTimer, 1000);
                            }
                        }
                        updateTimer();
                    });
                }
                
                function initializeEventListeners() {
                    $('.drink-name').click(function () {
                        const $this = $(this);
                        const menuorderID = $this.data('menuorder-id');
                        
                        $(this).toggleClass('cancel-text');
                        localStorage.setItem(`drink_cancel_${menuorderID}`, true);
                        
                        $.post('../Kitchen/updateNotif.php', {menuorderID: menuorderID}, function (response) {
                            if (response.includes("successfully")) {
                                console.log("Notification updated successfully for menuorderID: " + menuorderID);
                            } else {
                                console.error("Error updating notification status: " + response);
                            }
                        });
                    });
                    
                    $('.serve-all-btn').click(function () {
                        let tableNo = $(this).data('table-no');
                        let $tableContainer = $(`#table-container-${tableNo}`);
                        let orderIDs = [];
                        let menuorderIDs = [];
                        
                        $tableContainer.find('tbody tr').each(function () {
                            orderIDs.push($(this).data('order-id'));
                        });
                        
                        $tableContainer.find('tbody td').each(function () {
                            menuorderIDs.push($(this).data('menuorder-id'));
                        });
                        
                        if (orderIDs.length > 0) {
                            if (confirm(`Are you sure you want to serve all orders for Table ${tableNo}?`)) {
                                $.post('updateOrderStatusDrink.php', {orderIDs: orderIDs,menuorderIDs: menuorderIDs}, function (response) {
                                    if (response.includes("successfully")) {
                                        $tableContainer.fadeOut(500, function () {
                                            $(this).remove();
                                        });
                                        alert("Orders served successfully.");
                                        
                                        const servedOrderIDs = JSON.parse(response.split("successfully")[1]);
                                        servedOrderIDs.forEach(orderId => {
                                            localStorage.removeItem(`timer_${orderId}`);
                                        });
                                    } else {
                                        console.error("Error updating order status: " + response);
                                        alert("Error updating order status. Please try again.");
                                    }
                                });
                            }
                        } else {
                            console.error("No orders found for the selected table.");
                            alert("No orders found for the selected table.");
                        }
                    });
                }
                
                function restoreCancelTextState() {
                    $('.drink-name').each(function () {
                        const $this = $(this);
                        const menuorderID = $this.data('menuorder-id');
                        const isCanceled = localStorage.getItem(`drink_cancel_${menuorderID}`);
                        if (isCanceled) {
                            $this.addClass('cancel-text');
                        }
                    });
                }
                
                $('#categoryDropdown').change(function () {
                    let selectedOption = $(this).val();
                    let redirectUrl = '';
                    switch (selectedOption) {
                        case 'drinks':
                            redirectUrl = 'drinks.php';
                            break;
                        case 'hot':
                            redirectUrl = 'hot.php';
                            break;
                        case 'dessert':
                            redirectUrl = 'dessert.php';
                            break;
                        default:
                            redirectUrl = '';
                    }
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    }
                });
                
                refreshContent();
                setInterval(refreshContent, 10000);
            });
            
            function navigateToBack() {
                window.location.href = "../Kitchen/foodCategory.php";
            }
        </script>
    </body>
</html>
