<?php
session_start();

if (isset($_POST['index']) && isset($_POST['quantity'])) {
    $index = intval($_POST['index']);
    $quantity = intval($_POST['quantity']);

    if (isset($_SESSION['addedItems'][$index])) {
        $_SESSION['addedItems'][$index]['quantity'] = $quantity;
    }
}
?>