<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT staff.staffID, staff.first_name, staff.last_name, COUNT(*) AS total_orders
FROM orders
INNER JOIN menu_order ON orders.orderID = menu_order.orderID
INNER JOIN staff ON orders.staffID = staff.staffID
WHERE NOT (staff.first_name = 'Admin' AND staff.last_name = 'Aaron') 
AND NOT (staff.first_name = 'Kitchen' AND staff.last_name = 'Samantha')
GROUP BY staff.staffID, staff.first_name, staff.last_name
ORDER BY total_orders DESC";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$staffNames = [];
$totalOrders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $staffNames[] = $row['first_name'] . ' ' . $row['last_name'];
    $totalOrders[] = $row['total_orders'];
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sales Report</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <!-- Custom CSS -->
        <style>
        body {
            padding-top: 70px;
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
            <h1 class="header">Staff Productivity Report</h1>
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

            <canvas id="salesChart" width="800" height="400"></canvas>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Add event listener to the logout button
            document.getElementById('logoutButton').addEventListener('click', function (event) {
                // Prevent default action
                event.preventDefault();
                // Show confirmation dialog
                if (confirm('Are you sure you want to logout?')) {
                    // If confirmed, proceed with the logout
                    window.location.href = '/FYP_FoodOrderApp/index.php';
                }
            });
            
            // Prepare the data for the chart
        var staffNames = <?php echo json_encode($staffNames); ?>;
        var totalOrders = <?php echo json_encode($totalOrders); ?>;

        // Create the chart
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: staffNames,
                datasets: [{
                    label: 'Total Orders',
                    data: totalOrders,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    </body>
</html>


