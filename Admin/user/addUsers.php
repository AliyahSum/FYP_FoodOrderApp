<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Users</title>
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
            .switch{
                position:relative;
                display: inline-block;
                width:60px;
                height:34px;
                top: 20px;
            }
            .switch input{
                opacity:0;
                width:0;
                height:0;
            }
            .slider{
                position:absolute;
                cursor:pointer;
                top:0;
                left:0;
                right:0;
                bottom: 0;
                background-color:#cccccc;
                -webkit-transition:.4s;
                transitions:.4s;
            }
            .slider:before{
                position: absolute;
                content:"";
                height:26px;
                width:26px;
                left:4px;
                bottom:4px;
                background-color:white;
                -webkit-transition:.4s;
            }
            input:checked + .slider {
                background-color: #44D368;
            }
            input:focus + .slider {
                box-shadow: 0 0 1px #44D368;
            }
            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
            .slider.round {
                border-radius: 34px;
            }
            .slider.round:before {
                border-radius: 50%;
            }
            h1{
                text-align: center;
            }
            label{
                padding-top: 5px;
            }
            form{
                margin-top: -20px;
            }
            .input-submit{
                background-color: green;
                font-weight: bold;
                color: white;
                padding: 5px 263px;
                border-radius: 20px;
                border-style: none;
            }
            .backnav{
                margin-right: 10px;
            }            
            .admin{
                margin-top:-20px;
            }            
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Add Users</a>
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
                            <a class="btn btn-outline-light backnav" href="../../Admin/user/viewUsers.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Adding Users</h1><br>
        <div class="container d-flex justify-content-center align-items-center">
            <form id="postReview" method="post" action="../../Admin/user/doAddUsers.php">
                <label for="staffid">Staff ID:</label><br>
                <input type="text" size="70px" id="staffid" name="staffid" placeholder="STF001" required><br>

                <label for="fname">First Name:</label><br>
                <input type="text" size="70px" id="fname" name="fname" required><br>

                <label for="lname">Last Name:</label><br>
                <input type="text" size="70px" id="lname" name="lname" required><br>

                <label for="dob">DOB:</label><br>
                <input type="date" size="70px" id="dob" name="dob" required><br>

                <label for="email">Email:</label><br>
                <input type="email" size="70px" id="email" name="email" required><br>

                <label for="password">Password:</label><br>
                <input type="password" size="70px" id="password" name="password" required><br>

                <label for="cfmpassword">Confirm Password:</label><br>
                <input type="password" size="70px" id="cfmpassword" name="cfmpassword" required><br><br>
                
                <div class="admin">
                    Admin:
                    <label class="switch">
                        <input type="checkbox">
                        <span class ="slider round" onclick="setAdmin()"></span><br><br>
                    </label><br><br>
                </div>                
                <input type="hidden" name="adminInt" id="adminInt" value="1">                
                <input class="input-submit" id="submitButton" type="submit" value="Add"/>
            </form>  
        </div>
    </body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let adminInt = 0;
    function setAdmin() {
        adminInt = adminInt === 1 ? 0 : 1;
        document.getElementById('adminInt').value = adminInt;
    }
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '../../index.php';
        }
    });
    document.getElementById('submitButton').addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to add?')) {
            event.preventDefault();
        }
    });        
</script>
