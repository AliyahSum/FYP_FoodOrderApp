<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['table_number'])) {
    $table_number = $_SESSION['table_number'];
}

if (isset($_GET['table_number'])) {
    $table_number = $_GET['table_number'];
    $_SESSION['table_number'] = $table_number;
}

$query = "SELECT *
          FROM menu_item mi 
          INNER JOIN category c ON mi.categoryID = c.categoryID";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $arrItems[] = $row;
}

$notifQuery = "SELECT COUNT(*) as notif_count FROM menu_order WHERE notif = 1";
$notifResult = mysqli_query($link, $notifQuery);
$notifCount = mysqli_fetch_assoc($notifResult)['notif_count'];

mysqli_close($link);

if (!isset($_SESSION['addedItems'])) {
    $_SESSION['addedItems'] = [];
}
$addedItems = $_SESSION['addedItems'];

$itemAdded = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemName'])) {
    $itemID = $_POST['menuitemID'];
    $itemName = $_POST['itemName'];
    $serveLater = $_POST['serveLater'];
    $specialRequests = $_POST['specialRequests'];
    $itemOption = $_POST['item_option'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $addedItems[] = [
        'itemName' => $itemName,
        'menuitemID' => $itemID,
        'serveLater' => $serveLater,
        'specialRequests' => $specialRequests,
        'item_option' => $itemOption, 
        'quantity' => $quantity,
        'category' => $category
    ];

    $_SESSION['addedItems'] = $addedItems;
    $itemAdded = true;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Menu</title>
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
                padding-left: 25px;
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
            .backnav {
                left: 0%;
            }
            .btn-success {
                margin-left: 30px;
            }
            .card-img-top {
                height: 200px;
                object-fit: cover;
            }
            .category-row {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <a class="navbar-brand" href="#">Order Menu</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#appetizers">Appetizers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#breakfast-menu">Breakfast Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#lunch-menu">Lunch Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#teatime">Teatime</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#drinks">Drinks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#desserts">Desserts</a>
                        </li>
                    </ul>
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
                            <a class="nav-link btn btn-success" href="OrderTicket.php?table_number=<?php echo $table_number ?>">Order Ticket</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>       

        <div class="container mt-5">
            <?php
            $printedCategories = [];
            $printedItemIDs = [];

            foreach ($arrItems as $item) {
                $itemName = $item['item_name'];
                $itemDesc = $item['item_description'];
                $itemImage = $item['image'];
                $itemPrice = $item['price'];
                $itemAvail = $item['isAvailable'];
                $itemCat = $item['category_name'];
                $menuitemID = $item['menuitemID'];

                if (in_array($menuitemID, $printedItemIDs)) {
                    continue;
                }
                $printedItemIDs[] = $menuitemID;

                if (!in_array($itemCat, $printedCategories)) {
                    if (!empty($printedCategories)) {
                        echo '</div>'; 
                    }
                    $printedCategories[] = $itemCat;
                    echo '<h2 id="' . strtolower(str_replace(' ', '-', $itemCat)) . '">' . $itemCat . '</h2>';
                    echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 category-row">';
                }
                ?>
                <div class="col">
                    <div class="card">
                        <img src="../images/<?php echo $itemImage; ?>" class="card-img-top" alt="<?php echo $itemName; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $itemName; ?></h5>
                            <p class="card-text"><?php echo $itemDesc; ?></p>
                            <p class="card-text">$<?php echo number_format($itemPrice, 2); ?></p>
                            <?php if ($itemAvail): ?>
                                <button type="button" class="btn btn-primary addItemBtn" data-bs-toggle="modal" data-bs-target="#orderModal" 
                                        data-item-name="<?php echo $itemName; ?>" data-item-id="<?php echo $menuitemID; ?>" data-category="<?php echo $itemCat; ?>">
                                    Add
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary" disabled>
                                    Unavailable
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            if (!empty($printedCategories)) {
                echo '</div>'; 
            }
            ?>
        </div>

        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel">Order Item</h5>
                        <button type="button" class="btn-close" style="color: red;" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="orderForm" method="post" action="OrderMenu.php">
                            <div class="mb-3" id="donenessLevelContainer"></div>
                            <div class="mb-3" id="drinkOptionsContainer"></div>
                            <div class="mb-3">
                                <label for="serveLater" class="form-label">Serve later:</label>
                                <div>
                                    <input type="radio" id="serveLaterNo" name="serveLater" value="0" checked>
                                    <label for="serveLaterNo">No</label>
                                </div>
                                <div>
                                    <input type="radio" id="serveLaterYes" name="serveLater" value="1">
                                    <label for="serveLaterYes">Yes</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="specialRequests" class="form-label">Special Requests</label>
                                <textarea class="form-control" id="specialRequests" name="specialRequests" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                            </div>
                            <input type="hidden" id="itemName" name="itemName">
                            <input type="hidden" id="menuitemID" name="menuitemID">
                            <input type="hidden" id="itemOption" name="item_option">
                            <input type="hidden" id="category" name="category">
                            <input type="hidden" id="itemAdded" name="itemAdded" value="<?php echo $itemAdded ? '1' : '0'; ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-auto" style="color: white; background-color: red;" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" style="color: white; background-color: green;" id="addItemBtn">Add item</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var orderModal = document.getElementById('orderModal');

                var donenessMap = {
                    'NA': 1,
                    'Rare': 2,
                    'Medium Rare': 3,
                    'Medium': 4,
                    'Medium Well': 5,
                    'Well Done': 6
                };
                var drinkMap = {
                    'NA': 1,
                    'Hot': 2,
                    'Cold': 3
                };

                orderModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var itemName = button.getAttribute('data-item-name');
                    var itemId = button.getAttribute('data-item-id');
                    var itemCategory = button.getAttribute('data-category');
                    var donenessName = button.getAttribute('data-doneness-name') || 'NA';
                    var drinkName = button.getAttribute('data-drink-name') || 'NA';

                    var modalTitle = orderModal.querySelector('.modal-title');
                    modalTitle.textContent = 'Order ' + itemName;

                    var form = orderModal.querySelector('#orderForm');
                    form.reset();
                    form.querySelector('#itemName').value = itemName;
                    form.querySelector('#menuitemID').value = itemId;
                    form.querySelector('#category').value = itemCategory;

                    var donenessLevelContainer = document.getElementById('donenessLevelContainer');
                    var drinkOptionsContainer = document.getElementById('drinkOptionsContainer');
                    donenessLevelContainer.innerHTML = '';
                    drinkOptionsContainer.innerHTML = '';

                    if (itemCategory === 'Lunch Menu') {
                        var row1 = '', row2 = '';
                        Object.keys(donenessMap).forEach(function (key, index) {
                            var radioHtml = `
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="donenessLevel" id="doneness${key}" value="${donenessMap[key]}" ${donenessName === key ? 'checked' : ''}>
                                    <label class="form-check-label" for="doneness${key}">
                                        ${key}
                                    </label>
                                </div>
                            `;
                            if (index < 3) {
                                row1 += radioHtml;
                            } else {
                                row2 += radioHtml;
                            }
                        });

                        donenessLevelContainer.innerHTML = `
                            <label class="form-label">Doneness Level:</label>
                            <div class="row">
                                ${row1}
                            </div>
                            <div class="row">
                                ${row2}
                            </div>
                        `;
                    } else if (itemCategory === 'Drinks') {
                        Object.keys(drinkMap).forEach(function (key) {
                            drinkOptionsContainer.innerHTML += `
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="drinkOption" id="drink${key}" value="${drinkMap[key]}" ${drinkName === key ? 'checked' : ''}>
                                    <label class="form-check-label" for="drink${key}">
                                        ${key}
                                    </label>
                                </div>
                            `;
                        });

                        drinkOptionsContainer.innerHTML = `
                            <label class="form-label">Drink Options:</label>
                            <div class="row">
                                ${drinkOptionsContainer.innerHTML}
                            </div>
                        `;
                    }
                });

                document.getElementById('addItemBtn').addEventListener('click', function () {
                    var form = document.getElementById('orderForm');
                    var donenessLevel = form.querySelector('input[name="donenessLevel"]:checked');
                    var drinkOption = form.querySelector('input[name="drinkOption"]:checked');
                    var itemOption = '';

                    if (donenessLevel) {
                        itemOption = donenessLevel.value;
                    } else if (drinkOption) {
                        itemOption = drinkOption.value;
                    }

                    form.querySelector('#itemOption').value = itemOption;
                    form.submit();
                });

                var itemAdded = document.getElementById('itemAdded').value;
                if (itemAdded === '1') {
                    var alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Item successfully added to the order!';
                    document.body.insertBefore(alert, document.body.firstChild);
                    setTimeout(function () {
                        alert.remove();
                    }, 3000);
                }
            });

            function navigateToBack() {
                window.location.href = "../Server/tables.php";
            }
            function refreshPage() {
                window.location.reload();
            }
            setInterval(refreshPage, 30000); 
        </script>
    </body>
</html>
