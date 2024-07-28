<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_GET['staffId'])) {
    $staffID = $_GET['staffId'];
} else {
    die("Staff ID not provided!");
}

$query = "SELECT * FROM staff WHERE staffID = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("s", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("User not found!");
}
$stmt->close();
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit User</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
                top: 20px;
            }
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #44D368;
                -webkit-transition: .4s;
                transition: .4s;
            }
            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 30px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
            }
            input:checked + .slider {
                background-color: #cccccc;
            }
            input:focus + .slider {
                box-shadow: 0 0 -1px #ccccc;
            }
            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(-26px);
            }
            .slider.round {
                border-radius: 34px;
            }
            .slider.round:before {
                border-radius: 50%;
            }
            h1 {
                text-align: center;
            }
            label {
                padding-top: 5px;
            }
            form {
                margin-top: -20px;
            }
            .input-edit, .input-delete {
                font-weight: bold;
                color: white;
                padding: 5px 125px;
                border-radius: 20px;
                border-style: none;
            }
            .input-edit {
                background-color: green;
            }
            .input-delete {
                background-color: red;
                margin-top: 10px;
            }
            .backnav {
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Edit Users</a>
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
                            <a class="btn btn-outline-light backnav" href="/FYP_FoodOrderApp/Admin/user/viewUsers.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Editing Users</h1><br>
        <div class="container d-flex justify-content-center align-items-center">
            <form id="postReview" method="post" action="/FYP_FoodOrderApp/Admin/user/doEditUsers.php">
                <label for="staffid">Staff ID:</label><br>
                <input type="text" size="70px" id="staffid" name="staffID" value="<?php echo $staffID ?>" disabled><br>
                <input type="hidden" name="staffid" id="adminInt" value="<?php echo $staffID ?>">

                <label for="fname">First Name:</label><br>
                <input type="text" size="70px" id="fname" name="fname" value="<?php echo $row['first_name'] ?>" required><br>

                <label for="lname">Last Name:</label><br>
                <input type="text" size="70px" id="lname" name="lname" value="<?php echo $row['last_name'] ?>" required><br>

                <label for="dob">DOB:</label><br>
                <input type="date" size="70px" id="dob" name="dob" value="<?php echo $row['DOB'] ?>" required><br>

                <label for="email">Email:</label><br>
                <input type="email" size="70px" id="email" name="email" value="<?php echo $row['email'] ?>" required><br>

                <label for="password">Password:</label><br>
                <input type="password" size="70px" id="password" name="password" required><br>

                <label for="cfmpassword">Confirm Password:</label><br>
                <input type="password" size="70px" id="cfmpassword" name="cfmpassword" required><br>

                <div class="avail">
                    Availability:
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round" onclick="setAvail()"></span><br><br>
                    </label><br><br>
                </div>
                
                <input type="hidden" name="availInt" id="availInt" value="1">
                
                <input class="input-edit" type="submit" id="editButton" name="action" value="Edit"/>
                <input class="input-delete" type="submit" id="deleteButton" name="action" value="Delete"/>
            </form>  
        </div>
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let availInt = 1;
    
    function setAvail() {
        availInt = availInt === 1 ? 0 : 1;
        document.getElementById('availInt').value = availInt;
    }
    
            // Add event listener to the logout button
        document.getElementById('logoutButton').addEventListener('click', function(event) {
            // Prevent default action
            event.preventDefault();
            // Show confirmation dialog
            if (confirm('Are you sure you want to logout?')) {
                // If confirmed, proceed with the logout
                window.location.href = '/FYP_FoodOrderApp/index.php';
            }
        });
        
                                    // Add event listener to the logout button
        document.getElementById('editButton').addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to edit?')) {
                            event.preventDefault();
            }
        });
        
                            // Add event listener to the logout button
        document.getElementById('deleteButton').addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete?')) {
                            event.preventDefault();
            }
        });
</script>
