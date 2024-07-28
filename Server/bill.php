<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_SESSION['table_number'])) {
    $table_number = $_SESSION['table_number'];
}

if (isset($_GET['table_number'])) {
    $table_number = $_GET['table_number'];
}

$table_sessions = $_SESSION['table_sessions'];

$num_customers = $_SESSION['num_customers'];
$_SESSION['valid_table_sessions'] = [];

if (!isset($_SESSION['valid_table_sessions']) || count($_SESSION['valid_table_sessions']) == 99) {
    $_SESSION['valid_table_sessions'] = [];
}

$checkValid = 0;

$_SESSION['valid_table_sessions'][] = [];

    if (in_array($table_sessions, $_SESSION['usedTableSessions'])) {
        $checkValid = 1;
    } else {
        $checkValid = 0;
    }
    

    if ($checkValid == false) {
    die("No Orders Yet :("); // Handle the error as needed
}

// Subquery to get the most recent order ID for each table
$recent_order_query = "
    SELECT orders.tableID, MAX(orders.orderID) as recentOrderID
    FROM orders
    GROUP BY orders.tableID
";

// Main query to get the details of the most recent order
$query = "
SELECT menu_item.menuitemID, menu_item.item_name, SUM(menu_order.quantity) AS quantity, menu_order.special_request, menu_item.price
FROM menu_item
INNER JOIN menu_order ON menu_item.menuitemID = menu_order.menuitemID
INNER JOIN orders ON orders.orderID = menu_order.orderID
INNER JOIN cust_table ON cust_table.tableID = orders.tableID
INNER JOIN ($recent_order_query) roq ON roq.tableID = orders.tableID AND roq.recentOrderID = orders.orderID
WHERE cust_table.table_num = '$table_number'
GROUP BY menu_item.menuitemID, menu_item.item_name, menu_item.price
ORDER BY menu_item.menuitemID
";

$result = mysqli_query($link, $query) or die(mysqli_error($link));
$arrContent = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($arrContent)) {
    die("No order data found for the specified table."); // Handle the error as needed
}

$total_cost = 0;

// Get the order time from the first row (assuming all items have the same order time)
$order_time = isset($arrContent[0]['orderDateTime']) ? date("H:i", strtotime($arrContent[0]['orderDateTime'])) : "N/A";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paymentmethodID'])) {
    $paymentmethodID = $_POST['paymentmethodID'];
    $orderID = isset($arrContent[0]['orderID']) ? $arrContent[0]['orderID'] : null; // Ensure $orderID is set

    if ($orderID) {
        // Check if receipt already exists for the given orderID
        $sqlCheck = "SELECT receiptID FROM receipt WHERE orderID = ?";
        $stmtCheck = $link->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $orderID);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        $gst = 9; // GST value
        $sc = 10; // Service charge

        if ($stmtCheck->num_rows > 0) {
            // Update existing receipt
            $stmtCheck->bind_result($existingReceiptID);
            $stmtCheck->fetch();
            $sqlUpdate = "UPDATE receipt SET gst = ?, paymentmethodID = ?, sc = ? WHERE receiptID = ?";
            $stmtUpdate = $link->prepare($sqlUpdate);
            $stmtUpdate->bind_param("iiis", $gst, $paymentmethodID, $sc, $existingReceiptID);

            if ($stmtUpdate->execute()) {
                $message = "Receipt updated successfully.";
            } else {
                $message = "Error updating receipt: " . $stmtUpdate->error;
            }

            $stmtUpdate->close();
        } else {
            // Insert new receipt
            $sqlInsert = "INSERT INTO receipt (receiptID, gst, paymentmethodID, orderID, sc) 
                          VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $link->prepare($sqlInsert);
            $stmtInsert->bind_param("siiii", $receiptID, $gst, $paymentmethodID, $orderID, $sc);

            if ($stmtInsert->execute()) {
                $message = "Receipt added successfully.";
            } else {
                $message = "Error adding receipt: " . $stmtInsert->error;
            }

            $stmtInsert->close();
        }

        $stmtCheck->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bill</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .mt-5 {
                display: flex;
                flex-direction: column;
            }

            h4 {
                font-size: 40px;
                text-align: center;
                color: #ffffff;
                padding: 10px;
                padding-left: 230px;
            }

            .navbar {
                margin-bottom: 20px;
                padding: 10px;
            }

            .text-light {
                padding-left: 30px;
                padding-top: 20px;
            }

            .scrollable {
                max-height: 300px;
                overflow-y: auto;
            }

            .card {
                width: 100%;
                max-width: 1500px;
                max-height: 1000px;
            }

            h2 {
                font-size: 25px;
                padding-top: 10px;
            }

            .th {
                font: bold;
                font-size: 18px;
            }

            .cost {
                padding-right: 100px;
            }

            .mt-auto1 {
                padding-top: 10px;
            }

            h5 {
                font-size: 20px;
                padding-bottom: 10px;
            }

            .print-btn {
                width: 200px;
                height: 40px;
                font-size: 20px;
            }

            .mt-4 {
                align-items: center;
            }

            .tabletime {
                padding-top: 10px;
            }

            .card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .cost {
                margin-right: -900px;
            }

            /* Use a media query to add a break point at 800px: */
            @media screen and (max-width: 820px) {
                .body{
                    max-width: 100%;
                }

                .nav-item1 {
                    margin-right: 50px;
                }

                .print-btn {
                    margin-top: 20px;
                }

                .card {
                    width: 3000px;
                    max-width: 130%; /* Increase the width of the card to be more responsive */
                }

                .mt-auto1, .mt-auto2, .mt-auto3, .mt-auto4, .mt-auto5, .mt-auto6 {
                    margin-right: -60px;
                }

                .cost {
                    margin-right: -600px;
                }

            }

            @media screen and (max-width: 1180px) {
                .body{
                    max-width: 100%;
                }

                .nav-item1 {
                    margin-right: 50px;
                }

                .print-btn {
                    margin-top: 20px;
                }

                .card {
                    width: 1000px;
                    max-width: 100%; /* Increase the width of the card to be more responsive */
                }

                .cost {
                    margin-left: -160px;
                }


            </style>
        </head>
        <body>
            <nav class="navbar navbar-expand-xxl bg-dark">
                <div class="container-fluid">
                    <button class="btn btn-outline-light" onclick="navigateToBack()">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item1">
                            <h4>Bill</h4>
                        </li>
                    </ul>
                    <div>
                        <p class="text-light"><span id="current-date"></span></p>
                    </div>
                    <div>
                        <p class="text-light"><span id="current-time"></span></p>
                    </div>
                </div>
            </nav>

            <div class="container mt-5" id="bill-container">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="tablenum mb-1">Table <?php echo $table_number; ?></h2>
                        <p class="tabletime">Order time: <?php echo $order_time; ?></p>
                    </div>
                    <div class="card-body scrollable">
                        <div class="table-responsive scrollable-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Food ID</th>
                                        <th scope="col">Dish</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Special Request</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($arrContent); $i++) {
                                        $foodID = $arrContent[$i]['menuitemID'];
                                        $dish = $arrContent[$i]['item_name'];
                                        $quantity = $arrContent[$i]['quantity'];
                                        $specialRequest = isset($arrContent[$i]['special_request']) ? $arrContent[$i]['special_request'] : "-";
                                        $price = $arrContent[$i]['price'];
                                        $subtotal = $price * $quantity;
                                        $total_cost += $subtotal;
                                        ?>
                                        <tr>
                                            <td><?php echo $foodID; ?></td>
                                            <td><?php echo $dish; ?></td>
                                            <td><?php echo $quantity; ?></td>
                                            <td><?php echo $specialRequest; ?></td>
                                            <td><?php echo "$" . number_format($subtotal, 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                $gst_value = $total_cost * 0.09;
                $service_charge_value = $total_cost * 0.10;
                $overall_cost = $total_cost + $gst_value + $service_charge_value;
                $average_pax_cost = $overall_cost / $num_customers;
                ?>
                <div class="cost">
                    <h6 class="mt-auto1 text-end">Total Cost: <span id="total-cost"><b><?php echo "$" . number_format($total_cost, 2); ?></b></span></h6>
                    <h6 class="mt-auto2 text-end">GST (9%): <span id="gst"><b><?php echo "$" . number_format($gst_value, 2); ?></b></span></h6>
                    <h6 class="mt-auto3 text-end">Service Charge (10%): <span id="service-charge"><b><?php echo "$" . number_format($service_charge_value, 2); ?></b></span></h6>
                    <h6 class="mt-auto4 text-end">Overall Cost (GST + SC inclusive): <span id="overall-cost"><b><?php echo "$" . number_format($overall_cost, 2); ?></b></span></h6>
                    <h6 class="mt-auto5 text-end">Average Pax Cost: <span id="average-pax-cost"><b><?php echo "$" . number_format($average_pax_cost, 2); ?></b></span></h6>
                    <h6 class="mt-auto6 text-end">Payment Method: <span id="payment-method"><b><?php
                                if (isset($_POST["paymentmethodID"])) {
                                    if ($_POST["paymentmethodID"] == 1) {
                                        $payMethod = "NETS";
                                    } if ($_POST["paymentmethodID"] == 2) {
                                        $payMethod = "Mastercard";
                                    } if ($_POST["paymentmethodID"] == 3) {
                                        $payMethod = "Visa";
                                    } if ($_POST["paymentmethodID"] == 4) {
                                        $payMethod = "Others";
                                    }
                                    echo $payMethod;
                                }
                                ?></b></span></h6>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    Select Payment Method
                </button>
                <button class="btn btn-danger" onclick="confirmReset()">Reset Orders</button>
                </div>
            </div>

            <!-- Payment Method Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="bill.php?table_number=<?php echo $table_number; ?>">
                            <div class="mb-3">
                                <label for="paymentmethodID" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentmethodID" name="paymentmethodID" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option value="1">NETS</option>
                                    <option value="2">Mastercard</option>
                                    <option value="3">Visa</option>
                                    <option value="4">Others</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


            <div class="container mt-4 d-flex justify-content-end">
                <button class="print-btn btn btn-success" onclick="window.print()">Print</button>
            </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success mt-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                    // Function to update current date and time
                    function updateDateTime() {
                        const currentDate = new Date().toLocaleDateString();
                        const currentTime = new Date().toLocaleTimeString();
                        document.getElementById('current-date').innerText = currentDate;
                        document.getElementById('current-time').innerText = currentTime;
                    }

                function confirmReset() {
                    if (confirm('Are you sure you want to reset the orders?')) {
                        window.location.href = 'reset_order.php?table_number=<?php echo $table_number; ?>';
                    }
                }

                    window.onload = function () {
                        updateDateTime();
                        // Update date and time every second
                        setInterval(updateDateTime, 1000);

                        // Automatically show the payment method modal when the page loads
                        var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                        paymentModal.show();
                    };

                    // Close the modal when the form is submitted
                    document.getElementById('payment-form').addEventListener('submit', function (event) {
                        event.preventDefault();
                        var form = this;
                        var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                        paymentModal.hide();
                        setTimeout(function () {
                            form.submit();
                        }, 300); // Small delay to ensure the modal is completely hidden
                    });


                    function navigateToBack() {
                        // Redirect to the next category page (replace URL with desired destination)
                        window.location.href = "/FYP_FoodOrderApp/Server/tables.php"; // Change "kitchen.html" to the actual URL of the next page
                    }

            </script>
        </body>
    </html>
