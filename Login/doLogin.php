<?php
session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$staff_id = $_POST['staff_id'];
$password = $_POST['password'];

$hashed_password = sha1($password);

$sql = "SELECT * FROM staff WHERE staffID = ? AND password = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("ss", $staff_id, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffID = $row['staffID'];
    $admin = $row['admin_role'];
    $available = $row['isAvailable'];
    $delete = $row['isDelete'];

    if ($available == 1 && $delete == 0) {
        if ($admin == 1) {
            header("Location: admin.php");
        } else {
            header("Location: role.php");
        }
        
        $_SESSION['staffID'] = $staffID;        
    } else if ($available == 0 || $delete == 1) {
        echo "<div style='padding-top:300px;text-align:center;font-family:Arial, sans-serif;'>
            <h1>Account is currently disabled</h1>
            <p style='text-align:center;font-family:Arial, sans-serif;'>
            <a style='text-decoration:none;background-color:black;color:white;border-radius:12px;font-size:20px;padding:10px;' href='../index.php'>Head Back</a>
            </p>
            </div>";
    }   
    exit();
} else {
    echo "<div style='padding-top:300px;text-align:center;font-family:Arial, sans-serif;'>
        <h1 style='text-align:center;font-family:Arial;'>Invalid Staff ID or Password</h1>
        <p style='text-align:center;font-family:Arial, sans-serif;'>
        <a style='text-decoration:none;background-color:black;color:white;border-radius:12px;font-size:20px;padding:10px;' href='../index.php'>Head Back</a>
        </p>
        </div>";
}
$stmt->close();
$link->close();
?>
