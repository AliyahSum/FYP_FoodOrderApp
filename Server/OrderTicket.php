<?php
session_start();

if (!isset($_SESSION['addedItems'])) {
    $_SESSION['addedItems'] = [];
}
if (!isset($_SESSION['usedTableSessions'])) {
    $_SESSION['usedTableSessions'] = [];
}
if (!is_array($_SESSION['usedTableSessions'])) {
    $_SESSION['usedTableSessions'] = [];
}
if (count($_SESSION['usedTableSessions']) >= 99) {
    $_SESSION['usedTableSessions'] = [];
}
if (!isset($_SESSION['table_sessions'])) {
    $_SESSION['table_sessions'] = 1;
}

$addedItems = $_SESSION['addedItems'];
$staffID = $_SESSION['staffID'];

if (isset($_SESSION['table_number'])) {
    $table_number = $_SESSION['table_number'];
}
if (isset($_GET['table_number'])) {
    $table_number = $_GET['table_number'];
}

$table_sessions = $_SESSION['table_sessions'];
$lunchItemOptionsMap = [
    '1' => 'Not Applicable',
    '2' => 'Rare',
    '3' => 'Medium Rare',
    '4' => 'Medium',
    '5' => 'Medium Well',
    '6' => 'Well Done',
];
$drinksItemOptionsMap = [
    '1' => 'Not Applicable',
    '2' => 'Hot',
    '3' => 'Cold',
];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $notifQuery = "SELECT COUNT(*) as notif_count FROM menu_order WHERE notif = 1";
    $notifResult = mysqli_query($link, $notifQuery);
    $notifCount = mysqli_fetch_assoc($notifResult)['notif_count'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Singapore');
    $orderDateTime = date("Y-m-d H:i:s");
    $orderStatusHot = 0;
    $orderStatusDrink = 0;
    $orderStatusDessert = 0;

    $query = "SELECT tableID FROM cust_table WHERE table_num = '$table_number'";
    $result = mysqli_query($link, $query);

    if (!$result) {
        die("Error retrieving tableID: " . mysqli_error($link));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        die("Table number not found in the database.");
    }

    $tableID = $row['tableID'];

    if (!isset($_SESSION['usedTableSessions'])) {
        $_SESSION['usedTableSessions'] = [];
    }
    if (!in_array($table_sessions, $_SESSION['usedTableSessions'])) {
        $queryOrders = "INSERT INTO orders (orderDateTime, staffID, tableID, orderStatusHot, orderStatusDrink, orderStatusDessert)
                        VALUES ('$orderDateTime', '$staffID', '$tableID', '$orderStatusHot','$orderStatusDrink','$orderStatusDessert')";

        if (mysqli_query($link, $queryOrders)) {
            $orderID = mysqli_insert_id($link);
            $_SESSION['orderID'] = $orderID;
            $_SESSION['usedTableSessions'][] = $table_sessions;
        } else {
            die("Error inserting order: " . mysqli_error($link));
        }
    } else {
        $orderID = $_SESSION['orderID'];
    }
    if (!isset($orderID)) {
        die("Order ID is not set.");
    }

    foreach ($addedItems as $item) {
        $itemName = $item['itemName'];
        $serveLater = $item['serveLater'];
        $specialRequests = $item['specialRequests'];
        $itemOption = $item['item_option'];
        $quantity = $item['quantity'];
        $itemID = $item['menuitemID'];

        $serveLaterValue = ($serveLater === 'yes') ? 1 : 0;

        $queryMenuOrders = "INSERT INTO menu_order (serve_later, item_option, special_request, menuitemID, orderID, quantity, notif) VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $link->prepare($queryMenuOrders);
        $stmt->bind_param("iissii", $serveLaterValue, $itemOption, $specialRequests, $itemID, $orderID, $quantity);

        if ($stmt->execute()) {
            $message = "Order added successfully.";
        } else {
            $message .= " Error adding order: " . $stmt->error;
        }
        $stmt->close();
    }

    $_SESSION['addedItems'] = [];

    header("Location: OrderTicket.php?success=1");
    exit();
}
    mysqli_close($link);

function getItemOptionWord($category, $itemOption) {
    global $lunchItemOptionsMap, $drinksItemOptionsMap;

    if ($category === 'Lunch Menu') {
        return $lunchItemOptionsMap[$itemOption] ?? 'NA';
    } elseif ($category === 'Drinks') {
        return $drinksItemOptionsMap[$itemOption] ?? 'NA';
    }
    return 'NA';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Ticket</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                padding-top: 100px;
                padding-bottom: 100px;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
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
            .nav-link .fas.fa-bell {
                font-size: 20px;
                margin-right: 5px;
            }
            .btn-primary {
                margin-left: 30px;
            }
            .card-img-top {
                height: 200px;
                object-fit: cover;
            }
            .text-red {
                color: red;
            }
            .text-blue {
                color: blue;
            }
            .text-black {
                color: black;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Order Ticket</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link nav-notif" href="Notifications.php">
                                <i class="fas fa-bell"></i>Notifications
                                <?php if ($notifCount > 0): ?>
                                    <span class="badge bg-danger"><?php echo $notifCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="OrderMenu.php">Back to Menu</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <h2 class="text-center">Order Summary</h2>
            <p class="text-center">Table Number: <?php echo $table_number; ?></p>
            <form method="post" action="OrderTicket.php">
                <?php if (!empty($addedItems)): ?>
                    <ul class="list-group mt-3">
                        <?php foreach ($addedItems as $index => $item): ?>
                            <li id="item-<?php echo $index; ?>" class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $item['itemName']; ?></strong>
                                    <?php if ($item['serveLater'] === 'yes'): ?>
                                        <p class="text-red">Serve Later: Yes</p>
                                    <?php endif; ?>
                                    <?php if (!empty($item['specialRequests'])): ?>
                                        <p class="text-blue">Special Requests: <?php echo $item['specialRequests']; ?></p>
                                    <?php endif; ?>
                                    <?php if ($item['item_option'] !== '1' && ($item['category'] === 'Lunch Menu' || $item['category'] === 'Drinks')): ?>
                                        <p class="text-black">Item Option: <?php echo getItemOptionWord($item['category'], $item['item_option']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <div class="d-inline-flex align-items-center">
                                        <button type="button" class="btn btn-secondary btn-sm me-1" onclick="updateQuantity(<?php echo $index; ?>, -1)">-</button>
                                        <span id="quantity-<?php echo $index; ?>" class="mx-2"><?php echo $item['quantity']; ?></span>
                                        <button type="button" class="btn btn-secondary btn-sm me-1" onclick="updateQuantity(<?php echo $index; ?>, 1)">+</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(<?php echo $index; ?>)">Remove</button>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">Submit Order</button>
                    </div>
                <?php else: ?>
                    <p>No items added to the order yet.</p>
                <?php endif; ?>
            </form>
        </div>

        <script>
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                            alert("Order submitted successfully!");
            <?php endif; ?>

            function updateQuantity(index, delta) {
                const quantityElement = document.getElementById('quantity-' + index);
                let currentQuantity = parseInt(quantityElement.innerText);
                currentQuantity += delta;
                if (currentQuantity < 1) {
                    currentQuantity = 1;
                }
                quantityElement.innerText = currentQuantity;

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "updateQuantity.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("index=" + index + "&quantity=" + currentQuantity);

                if (currentQuantity <= 0) {
                    const itemElement = document.getElementById('item-' + index);
                    itemElement.parentNode.removeChild(itemElement);
                }
            }

            function removeItem(index) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "removeItem.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const itemElement = document.getElementById('item-' + index);
                        itemElement.parentNode.removeChild(itemElement);
                    }
                };
                xhr.send("index=" + index);
            }            
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
