<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f9fa;
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
        .date, .time {
            margin-bottom: 20px;
            padding: 15px 30px;
            border-style: solid;
            border-width: 5px;
            border-radius: 30px;
        }
        .date i, .time i {
            font-size: 80px;
            margin-right: 10px;
        }
        .date h1, .time h1 {
            display: inline;
            font-size: 60px;
            font-weight: bold;
        }
        .grid-container {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
            gap: 50px;
            padding: 20px;
        }
        .btn-primary {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 180px;
            height: 150px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-radius: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary i {
            font-size: 36px;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .logOutNav {
            margin: 5px;
        }
        .grid-date-time {
            display: grid;
            grid-template-columns: auto auto;
            gap: 150px;
            padding-top: 120px;
            padding-bottom: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Home</a>
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
                        <a class="btn btn-outline-light backnav" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="grid-date-time">
        <div class="grid-item date">
            <i class="fas fa-calendar-alt"></i>
            <h1 id="currentDate"></h1>
        </div>
        <div class="grid-item time">
            <i class="far fa-clock"></i>
            <h1 id="currentTime"></h1>
        </div>
    </div>
    
    <div class="grid-container">
        <div class="grid-item">
            <a class="btn btn-primary" href="/FYP_FoodOrderApp/Admin/tables/tables_admin.php" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Tables">Tables<br><i class="fas fa-couch"></i></a>
        </div>
        <div class="grid-item">
            <a class="btn btn-primary" href="/FYP_FoodOrderApp/Admin/user/viewUsers.php" data-bs-toggle="tooltip" data-bs-placement="top" title="View and Manage Users">Users<br><i class="fas fa-user"></i></a>
        </div>
        <div class="grid-item">
            <a class="btn btn-primary" href="/FYP_FoodOrderApp/Admin/menu/viewMenu.php" data-bs-toggle="tooltip" data-bs-placement="top" title="View and Manage Menu Items">Menu<br><i class="fas fa-utensils"></i></a>
        </div>
        <div class="grid-item">
            <a class="btn btn-primary" href="/FYP_FoodOrderApp/Admin/orders/viewAllOrders.php" data-bs-toggle="tooltip" data-bs-placement="top" title="View All Orders">Orders<br><i class="fas fa-list-alt"></i></a>
        </div>
        <div class="grid-item">
            <a class="btn btn-primary" href="/FYP_FoodOrderApp/Admin/reports/report.php" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Reports">Reports<br><i class="fas fa-chart-line"></i></a>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript to get the current date
        const date = new Date();
        const formattedDate = date.toDateString(); // Format the date to a readable string
        document.getElementById('currentDate').innerText = formattedDate; // Display the date in the HTML element
        
        // Function to update the time
        function updateTime() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const formattedTime = `${hours}:${minutes}`;
            document.getElementById('currentTime').innerText = formattedTime;
        }

        // Update the time every second
        setInterval(updateTime, 1000);

        // Initial call to display the time immediately
        updateTime();

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
        
                $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
