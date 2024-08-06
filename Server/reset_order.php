<?php
session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_GET['table_number'])) {
    $table_number = $_GET['table_number'];
    $query = "UPDATE cust_table SET isAvailable = 1 WHERE table_num = '$table_number'";
    mysqli_query($link, $query) or die(mysqli_error($link));
    ($_SESSION['table_number']);
    ($_SESSION['num_customers']);
}

unset($_SESSION['order_data']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Orders</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2>Orders Reset</h2>
                </div>
                <div class="card-body">
                    <p>All orders have been reset successfully.</p>
                    <button class="btn btn-success" onclick="goToTables()">Go to Tables</button>
                </div>
            </div>
        </div>

        <script>
            function goToTables() {
                window.location.href = 'tables.php';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzq9p1FWE6pZZe8qK5G/5rs1i5i3eVd7CygKlv2C0EG4" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93qGm6A9d8H2LrVo2t3BRgIiMGgiZ1MtkD6tK62mMZlOwYBievs5I2t/hm0EG4" crossorigin="anonymous"></script>
    </body>
</html>
