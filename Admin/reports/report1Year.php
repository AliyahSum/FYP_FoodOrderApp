<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$query = "SELECT MONTH(orders.orderDateTime) AS month, SUM(menu_order.quantity * menu_item.price) AS total_sales
          FROM orders
          INNER JOIN menu_order ON orders.orderID = menu_order.orderID
          INNER JOIN menu_item ON menu_order.menuitemID = menu_item.menuitemID
          WHERE YEAR(orders.orderDateTime) = ?
          GROUP BY MONTH(orders.orderDateTime)
          ORDER BY month";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $year);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$months = [];
$totalSales = [];

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = date('F', mktime(0, 0, 0, $row['month'], 10));
    $totalSales[] = $row['total_sales'];
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Total Sales Throughout the Year</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                padding-top: 120px;
                justify-content: center;
                align-items: center;
            }
            .navbar {
                background-color: #343a40;
            }
            .navbar-brand {
                font-size: 40px;
            }
            .navbar-nav .nav-link {
                color: #ffffff;
            }
            .navbar-nav .nav-link:hover {
                color: #cccccc;
            }        
            .backnav {
                margin-right: 10px;
            }        
            .header {
                text-align: center;
                margin-top:-40px
            }        
            .mt-5 {         
                max-width: 900px;
            }        
            .monthlink{
                font-size: 20px;
                text-decoration: none;
            }        
            .print-btn{
                margin-right: 10px;
            }        
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Report</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../../Login/admin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/tables/tables_admin.php">Tables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/user/viewUsers.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/menu/viewMenu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/orders/viewAllOrders.php">View All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/reports/report.php">Reports</a>
                    </li>
                </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button class="print-btn btn btn-success" onclick="window.print()">Print</button>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light backnav" href="../../Admin/reports/report.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    
        <div class="container mt-5">
            <h1 class="header">Total Sales Throughout the Year <a class="monthlink" href="../../Admin/reports/report1Month.php">or by month</a></h1>
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col" style="padding-left: 200px;">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year">
                            <?php
                            $currentYear = date('Y');
                            for ($y = 2020; $y <= $currentYear; $y++) {
                                echo "<option value='$y'" . ($y == $year ? ' selected' : '') . ">$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col align-self-end">
                        <button type="submit" class="btn btn-primary">View Report</button>
                    </div>
                </div>
            </form>
            <canvas id="salesChart"></canvas>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                const salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($months); ?>,
                        datasets: [{
                            label: 'Total Sales',
                            data: <?php echo json_encode($totalSales); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: true
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Sales ($)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                });
            });
            document.getElementById('logoutButton').addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = '../../index.php';
                }
            });
        </script>
    </body>
</html>
