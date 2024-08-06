<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT menu_item.item_name, SUM(menu_order.quantity * menu_item.price) AS total_sales
        FROM menu_order
        INNER JOIN menu_item ON menu_order.menuitemID = menu_item.menuitemID
        INNER JOIN orders ON menu_order.orderID = orders.orderID
        GROUP BY menu_item.item_name
        ORDER BY total_sales DESC
        LIMIT 10;";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$menuItems = [];
$totalSales = [];

while ($row = mysqli_fetch_assoc($result)) {
    $menuItems[] = $row['item_name'];
    $totalSales[] = $row['total_sales'];
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sales Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
        }        
        .mt-5 {         
            max-width: 900px;
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
        
        <div class="container">
            <h1 class="header">Top 10 Sales for Menu Item</h1>
            <div class="container mt-5">
                <form method="GET" action="">
                    <div class="row mb-3" style="margin-top: -30px;">
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
            </div>
            <canvas id="salesChart" width="200" height="70"></canvas>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const menuItems = <?php echo json_encode($menuItems); ?>;
            const totalSales = <?php echo json_encode($totalSales); ?>;

            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: menuItems,
                    datasets: [
                        {
                            label: 'Total Sales ($)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: totalSales
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
            document.getElementById('logoutButton').addEventListener('click', function (event) {
                event.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = '../../index.php';
                }
            });
        </script>
    </body>
</html>


