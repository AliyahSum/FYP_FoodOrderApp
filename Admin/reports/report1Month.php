<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$query = "SELECT DAY(orders.orderDateTime) AS day, SUM(menu_order.quantity * menu_item.price) AS total_sales
          FROM orders
          INNER JOIN menu_order ON orders.orderID = menu_order.orderID
          INNER JOIN menu_item ON menu_order.menuitemID = menu_item.menuitemID
          WHERE MONTH(orders.orderDateTime) = ? AND YEAR(orders.orderDateTime) = ?
          GROUP BY DAY(orders.orderDateTime)
          ORDER BY day";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "ii", $month, $year);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$days = [];
$totalSales = [];

while ($row = mysqli_fetch_assoc($result)) {
    $days[] = $row['day'];
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
    <title>Total Sales Throughout the Month</title>
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
            margin-top:-40px;
            
        }
        
        .mt-5 {         
            max-width: 900px;
        }
        
        .yearlink{
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
                        <a class="nav-link" href="/FYP_FoodOrderApp/Login/admin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/table/tablesAdmin.php">Tables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/user/viewUsers.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/menu/viewMenu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/orders/viewAllOrders.php">View All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/reports/Report.php">Reports</a>
                    </li>
                </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button class="print-btn btn btn-success" onclick="window.print()">Print</button>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light backnav" href="/FYP_FoodOrderApp/Admin/reports/report.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <div class="container mt-5">
        <h1 class="header">Total Sales throughout the Month <a class="yearlink" href="/FYP_FoodOrderApp/Admin/reports/report1Year.php">or by year</a></h1>
        
        <form method="GET" action="">
            <div class="row mb-3" >
                <div class="col" style="padding-left: 150px;">
                    <label for="month" class="form-label">Month</label>
                    <select class="form-select" id="month" name="month">
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $monthName = date('F', mktime(0, 0, 0, $m, 10));
                            echo "<option value='$m'" . ($m == $month ? ' selected' : '') . ">$monthName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
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
                    labels: <?php echo json_encode($days); ?>,
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
                                text: 'Day'
                            }
                        }
                    }
                }
            });
        });

        document.getElementById('logoutButton').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/FYP_FoodOrderApp/index.php';
            }
        });
    </script>
</body>
</html>