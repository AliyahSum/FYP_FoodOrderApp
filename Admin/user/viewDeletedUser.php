<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT * FROM staff
          WHERE isDelete = 1";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];

while ($row = mysqli_fetch_array($result)) {
    $arrContent[] = $row;
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Deleted Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <style>
        body {
            padding-top: 100px;
            display: flex;
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
        
        .backnav{
            margin-right: 20px;
        }
        
        .addnav{
            margin-right: 20px;
        }
        
        .btn-success {
            align-items: center;
            padding: 5px 80px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">View Deleted Users</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Login/admin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/tables/tables_admin.php">Tables</a>
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
                        <a class="nav-link" href="/FYP_FoodOrderApp/Admin/reports/report.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-light backnav" href="/FYP_FoodOrderApp/Admin/user/viewUsers.php">Back</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                    </li>
                </ul>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <table id="userTable" class="table">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Available</th>
                    <th>Recover</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($arrContent); $i++) {
                    $staffID = $arrContent[$i]['staffID'];
                    $firstName = $arrContent[$i]['first_name'];
                    $lastName = $arrContent[$i]['last_name'];
                    $dob = $arrContent[$i]['DOB'];
                    $email = $arrContent[$i]['email'];
                    $admin = $arrContent[$i]['admin_role'];
                    $available = $arrContent[$i]['isAvailable'];
                ?>
                <tr>
                    <td><?php echo $staffID ?></td>
                    <td><?php echo $firstName . " " . $lastName ?></td>
                    <td><?php echo $dob ?></td>
                    <td><?php echo $email ?></td>
                    <td>
                        <?php
                        if ($admin == 0) {
                            echo "";
                        } else if ($admin == 1) {
                            echo "<div style=color:blue>Admin</div>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($available == 0) {
                            echo "<div style=color:red>Not Available</div>";
                        } else if ($available == 1) {
                            echo "<div style=color:#30A04C>Available</div>";
                        }
                        ?>
                    </td>
                    <td>
                        <form method="get" action="doRecoverUser.php">
                            <input type="hidden" name="staffId" value="<?php echo $staffID; ?>"/>
                            <input id="recoverBtn" type="submit" value="Recover"/>
                        </form>
                    </td> 
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
            
            // Add event listener to the logout button
            $('#logoutButton').on('click', function(event) {
                // Prevent default action
                event.preventDefault();
                // Show confirmation dialog
                if (confirm('Are you sure you want to logout?')) {
                    // If confirmed, proceed with the logout
                    window.location.href = '/FYP_FoodOrderApp/index.php';
                }
            });
            
                        $('#recoverBtn').on('click', function(event) {
                if (!confirm('Are you sure you want to recover?')) {
                    event.preventDefault();
                }
            });
        });

    </script>
</body>
</html>
