<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Menu Item</title>
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
            h1{
                text-align: center;
            }
            form{
                margin-top: -20px;
            }
            .backnav{
                margin-right: 10px;
            }
            .input-submit{
                background-color: green;
                font-weight: bold;
                color: white;
                padding: 5px 263px;
                border-radius: 20px;
                border-style: none;
            }
            .heading{
                font-weight: bold;
                padding-top: 10px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Add Menu Item</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../../Login/admin.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Admin/table/tablesAdmin.php">Tables</a>
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
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Adding Menu Item</h1><br>
        <div class="container d-flex justify-content-center align-items-center">
            <form id="postReview" method="post" action="../../Admin/menu/doAddMenuItem.php" enctype="multipart/form-data">
                <label class="heading" for="miID">Menu Item ID:</label><br>
                <input type="text" size="70px" id="staffid" name="miID" placeholder="ITM001" required><br>

                <label class="heading" for="name">Name:</label><br>
                <input type="text" size="70px" id="name" name="name" required><br>

                <label class="heading" for="desc">Description:</label><br>
                <textarea rows="3" cols="73" id="desc" name="desc" required></textarea><br>

                <label class="heading" for="image">Image (Recommended:1200x865):</label><br>
                <input type="file" id="image" name="image" required><br>

                <label class="heading" for="price">Price:</label><br>
                <input type="number" size="70px" id="price" name="price" step="any" required><br>

                <label class="heading">Station:</label><br>
                <input type="radio" id="drinks" name="station" value="STN1">
                <label for="drinks">Drinks</label><br>
                <input type="radio" id="hot" name="station" value="STN2">
                <label for="hot">Hot</label><br>
                <input type="radio" id="dessert" name="station" value="STN3">
                <label for="dessert">Dessert</label><br>

                <label class="heading">Category:</label><br>
                <input type="radio" id="appe" name="category" value="CAT01">
                <label for="appe">Appetizers</label><br>
                <input type="radio" id="bf" name="category" value="CAT02">
                <label for="bf">Breakfast Menu</label><br>
                <input type="radio" id="lunch" name="category" value="CAT03">
                <label for="lunch">Lunch Menu</label><br>
                <input type="radio" id="tea" name="category" value="CAT04">
                <label for="tea">Teatime</label><br>
                <input type="radio" id="drink" name="category" value="CAT05">
                <label for="drink">Drinks</label><br>
                <input type="radio" id="dessert" name="category" value="CAT06">
                <label for="dessert">Desserts</label><br>

                <label class="heading" for="prep">Preparation Time (minutes):</label><br>
                <input type="number" size="70px" id="prep" name="prep" required><br><br>                              

                <input class="input-submit" id="submitButton" name="submit" type="submit" value="Add"/>
            </form>  
        </div>
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('logoutButton').addEventListener('click', function (event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '../../index.php';
        }
    });

    document.getElementById('submitButton').addEventListener('click', function (event) {
        if (!confirm('Are you sure you want to add?')) {
            event.preventDefault();
        }
    });
</script>


