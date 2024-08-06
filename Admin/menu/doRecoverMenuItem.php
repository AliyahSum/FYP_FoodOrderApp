<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $menuItemId = $_GET['menuItemId'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

    $query = "UPDATE menu_item SET isDelete = 0 WHERE menuitemID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $menuItemId);

    if ($stmt->execute()) {
        $message = "Menu Item recovered successfully.";
    } else {
        $message = "Error recovering Menu Item: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($link);

    header("Location: viewDeletedMenu.php?message=" . urlencode($message));
    exit();
} else {
    header("Location: viewDeletedMenu.php?message=" . urlencode("Invalid request method."));
    exit();
}
?>
