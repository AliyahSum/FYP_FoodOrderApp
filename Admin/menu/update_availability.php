<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $availability = isset($_POST['availability']) ? intval($_POST['availability']) : 0;
    $itemID = isset($_POST['itemID']) ? intval($_POST['itemID']) : 0;

    if ($itemID > 0) {
        $query = "UPDATE menu_item SET isAvailable = ? WHERE itemID = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $availability, $itemID);
        if (mysqli_stmt_execute($stmt)) {
            echo 'Availability updated successfully';
        } else {
            echo 'Failed to update availability';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'Invalid item ID';
    }
}
mysqli_close($link);
?>