<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if(isset($_POST['orderIDs']) && is_array($_POST['orderIDs']) && isset($_POST['menuorderIDs']) && is_array($_POST['menuorderIDs'])) {
    $orderIDs = $_POST['orderIDs'];
    $menuorderIDs = $_POST['menuorderIDs'];
    $orderIDsStr = implode(',', array_map('intval', $orderIDs)); // Ensure safe handling of order IDs
    $menuorderIDsStr = implode(',', array_map('intval', $menuorderIDs)); 
    
    $updateNotifQuery = "UPDATE menu_order SET notif = 1 WHERE menuorderID IN ($menuorderIDsStr) AND notif = 0";
    mysqli_query($link, $updateNotifQuery) or die(mysqli_error($link));

    $updateOrderStatusQuery = "UPDATE orders SET orderStatusDrink = 1 WHERE orderID IN ($orderIDsStr)";
    mysqli_query($link, $updateOrderStatusQuery) or die(mysqli_error($link));
    
    echo "Orders served successfully. Order IDs: " . json_encode($orderIDs);
} else {
    echo "No valid order IDs received";
}
mysqli_close($link);