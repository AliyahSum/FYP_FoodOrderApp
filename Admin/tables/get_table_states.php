<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT table_num, isAvailable
          FROM cust_table";

$result = mysqli_query($link, $query);

$tableStates = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tableStates[] = $row;
}

mysqli_close($link);

echo json_encode($tableStates);
?>
