<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $staffId = $_GET['staffId'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

    $query = "UPDATE staff SET isDelete = 0 WHERE staffID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $staffId);

    if ($stmt->execute()) {
        $message = "User recovered successfully.";
    } else {
        $message = "Error recovering User: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($link);

    header("Location: viewDeletedUser.php?message=" . urlencode($message));
    exit();
} else {
    header("Location: viewDeletedUser.php?message=" . urlencode("Invalid request method."));
    exit();
}
?>
