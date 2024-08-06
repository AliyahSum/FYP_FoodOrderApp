<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT * FROM menu_item
          INNER JOIN category ON menu_item.categoryID = category.categoryID
          INNER JOIN station ON menu_item.stationID = station.stationID
          WHERE isDelete = 1
          ORDER BY menuitemID";

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
        <title>View Deleted Menu Items</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

            .backnav {
                margin-right: 15px;
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
                <a class="navbar-brand" href="#">View Deleted Menu Items</a>
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
                            <a class="btn btn-outline-light backnav" href="../../Admin/menu/viewMenu.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <?php
            if (isset($_GET['message'])) {
                echo '<div class="alert alert-info" role="alert">' . htmlspecialchars($_GET['message']) . '</div>';
            }
            ?>
            <table id="menuTable" class="table">
                <thead>
                    <tr>
                        <th>Food ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Station</th>
                        <th>Price</th>
                        <th>Prep Time (mins)</th>
                        <th>Available</th>
                        <th>Recover</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($arrContent); $i++) {
                        $foodID = $arrContent[$i]['menuitemID'];
                        $image = $arrContent[$i]['image'];
                        $name = $arrContent[$i]['item_name'];
                        $desc = $arrContent[$i]['item_description'];
                        $category = $arrContent[$i]['category_name'];
                        $station = $arrContent[$i]['station_name'];
                        $price = $arrContent[$i]['price'];
                        $available = $arrContent[$i]['isAvailable'];
                        $prepTime = $arrContent[$i]['prepTime'];
                    ?>
                    <tr>
                        <td><?php echo $foodID ?></td>
                        <td><?php echo '<img src="../../images/' . $image . '" alt="' . $name . '" style="width:100px;height:auto;">'; ?></td>
                        <td><?php echo $name ?></td>
                        <td><?php echo $desc ?></td>
                        <td><?php echo $category ?></td>
                        <td><?php echo $station ?></td>
                        <td><?php echo $price ?></td>
                        <td><?php echo $prepTime ?></td>
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
                            <form method="get" action="doRecoverMenuItem.php">
                                <input type="hidden" name="menuItemId" value="<?php echo $foodID; ?>"/>
                                <input id="recoverBtn" type="submit" value="Recover" class="btn btn-success"/>
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
                $('#menuTable').DataTable();

                $('#logoutButton').on('click', function(event) {
                    event.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = '../../index.php';
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
