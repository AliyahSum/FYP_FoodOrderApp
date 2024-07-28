<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'];

    if (isset($_SESSION['addedItems'][$index])) {
        array_splice($_SESSION['addedItems'], $index, 1);
    }

    echo 'Item removed';
}
?>