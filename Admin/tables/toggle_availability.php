<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

// Create connection
$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

// Get form data
$tableNum = $_POST['tableNum'];
$isAvailable = $_POST['isAvailable'];

// Update isAvailable in the database for the specified table number
$query = "UPDATE cust_table
          SET isAvailable = ? 
          WHERE table_num = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "ii", $isAvailable, $tableNum);

if (mysqli_stmt_execute($stmt)) {
    echo "Availability updated successfully for Table Number $tableNum.";
} else {
    echo "Error updating record: " . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
