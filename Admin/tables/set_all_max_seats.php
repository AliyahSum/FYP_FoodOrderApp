<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

// Create connection
$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

// Get form data
$allMaxSeats = $_POST['allMaxSeats'];

// Update max_cust in the database for all tables
$query = "UPDATE cust_table SET max_cust = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $allMaxSeats);

if (mysqli_stmt_execute($stmt)) {
    echo "Maximum seats updated successfully for all tables.";
} else {
    echo "Error updating records: " . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
