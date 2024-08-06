<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_POST['menuorderID'])) {
    $menuorderID = $_POST['menuorderID'];
    
    $query = "UPDATE menu_order SET notif = 1 WHERE menuorderID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $menuorderID);
    
    if ($stmt->execute()) {
        echo "Notification updated successfully for menuorderID: " . $menuorderID;
    } else {
        echo "Error updating notification status: " . $stmt->error;
    }    
    $stmt->close();
}
mysqli_close($link);
?>
