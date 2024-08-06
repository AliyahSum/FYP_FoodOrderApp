<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_POST['clear1'])) {
    $query = "UPDATE orders SET orderStatusDrink = 2 WHERE orderStatusDrink = 1";
    $result = mysqli_query($link, $query);

    if ($result) {
        echo "Orders cleared successfully.";
    } else {
        echo "Error clearing orders: " . mysqli_error($link);
    }
}
    
if (isset($_POST['clear2'])) {
    $query = "UPDATE orders SET orderStatusHot = 2 WHERE orderStatusHot = 1";
    $result = mysqli_query($link, $query);

    if ($result) {
        echo "Orders cleared successfully.";
    } else {
        echo "Error clearing orders: " . mysqli_error($link);
    }
}    
    
if (isset($_POST['clear3'])) {
    $query = "UPDATE orders SET orderStatusDessert = 2 WHERE orderStatusDessert = 1";
    $result = mysqli_query($link, $query);

    if ($result) {
        echo "Orders cleared successfully.";
    } else {
        echo "Error clearing orders: " . mysqli_error($link);
    }        
    mysqli_close($link);
}
?>

