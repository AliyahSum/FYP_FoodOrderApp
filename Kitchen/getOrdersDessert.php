<!DOCTYPE html>
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
        WHERE orders.orderStatusDessert = 0 AND stationID = 'STN3'
        ORDER BY menu_order.orderID ASC";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];
while ($row = mysqli_fetch_array($result)) {
    $tableNo = $row['table_num'];
    if (!isset($arrContent[$tableNo])) {
        $arrContent[$tableNo] = [
            'table_num' => $tableNo,
            'orderDateTime' => $row['orderDateTime'],
            'orders' => [],
            'menu_order' => []
        ];
    }
    $arrContent[$tableNo]['orders'][] = [
        'quantity' => $row['quantity'],
        'item_name' => $row['item_name'],
        'special_request' => $row['special_request'],
        'serve_later' => $row['serve_later'],
        'prepTime' => isset($row['prepTime']) ? $row['prepTime'] : 'N/A',
        'orderID' => $row['orderID'],
        'menuorderID' => $row['menuorderID']
    ];
}
mysqli_close($link);

foreach ($arrContent as $tableNo => $tableData) {
    ?>
    <div id="table-container-<?php echo $tableNo; ?>">
        <div class="row align-items-center mb-2">
            <div class="col-md-8 table-header">
                <h3>Table No. <?php echo $tableNo; ?></h3>
                <span class="order-time"><?php echo $tableData['orderDateTime']; ?></span>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-primary serve-all-btn" data-table-no="<?php echo $tableNo; ?>">Complete</button>
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
                    <th>Countdown</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tableData['orders'] as $order) { ?>
                    <tr data-order-id="<?php echo $order['orderID']; ?>" data-prep-time="<?php echo $order['prepTime']; ?>">
                        <td><?php echo $order['quantity']; ?></td>
                        <td class="dessert-name" data-menuorder-id="<?php echo $order['menuorderID']; ?>"><?php echo $order['item_name']; ?></td>
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
                        <td class="timer-cell"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}
if (empty($arrContent)) {
    ?>
    <p>No orders found.</p>
    <?php
}
?>