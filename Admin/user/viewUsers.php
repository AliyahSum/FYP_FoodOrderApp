<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT * FROM staff
          WHERE isDelete = 0";

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
        <title>View Users</title>
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
                <a class="navbar-brand" href="#">View Users</a>
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
                            <a class="btn btn-outline-success addnav" href="../../Admin/user/addUsers.php">Add Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger backnav" href="../../Admin/user/viewDeletedUser.php">Deleted Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
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
                        <th>Edit</th>
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
                            <form method="get" action="../../Admin/user/editUsers.php">
                                <input type="hidden" name="staffId" value="<?php echo $staffID; ?>"/>
                                <input class="edit-btn" type="submit" value="Edit"/>
                            </form>
                        </td> 
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <a class="btn btn-success" href="../../Admin/user/addUsers.php">Add New Users</a>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable();

                $('#logoutButton').on('click', function(event) {
                    event.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = '../../index.php';
                    }
                });
            });
        </script>
    </body>
</html>
