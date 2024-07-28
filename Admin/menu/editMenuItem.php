<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if (isset($_GET['menuItemId'])) {
    $miID = $_GET['menuItemId'];
} else {
    die("Menu ID not provided!");
}

$query = "SELECT * FROM menu_item WHERE menuitemID = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("s", $miID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Menu item not found!");
}
$stmt->close();
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
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
            width: 61px;
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
            background-color: #cccccc;
            -webkit-transition: .4s;
            transition: .4s;
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
                -webkit-transform: translateX(-26px);
                -ms-transform: translateX(-26px);
                transform: translateX(26px);
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
        
            .heading{
                font-weight: bold;
                padding-top: 10px;
            }
        
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Edit Menu Item</a>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-light backnav" href="/FYP_FoodOrderApp/Admin/menu/viewMenu.php">Back</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light logOutNav" id="logoutButton" href=/FYP_FoodOrderApp/"index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Editing Menu Item</h1><br>
    <div class="container d-flex justify-content-center align-items-center">
<form id="postReview" method="post" action="/FYP_FoodOrderApp/Admin/menu/doEditMenuItem.php" enctype="multipart/form-data">
    <label class="heading" for="miID">Menu Item ID:</label><br>
    <input type="text" size="70px" id="miId" name="miID" value="<?php echo $miID ?>" disabled><br>
    <input type="hidden" name="menuitemid" value="<?php echo $miID ?>">

    <label class="heading" for="name">Name:</label><br>
    <input type="text" size="70px" id="name" name="name" value="<?php echo $row['item_name'] ?>" required><br>

    <label class="heading" for="desc">Description:</label><br>
    <textarea rows="3" cols="73" id="desc" name="desc" required><?php echo $row['item_description'] ?></textarea><br>

    <label class="heading" for="image">Current Image:</label><br>
    <img src="/FYP_FoodOrderApp/Images/<?php echo $row['image'] ?>" alt="Current Image" width="200"><br>
    <input type="hidden" name="image" value="<?php echo $row['image'] ?>">
    <label class="heading" for="newimage">Upload New Image (Recommended:1200x865):</label><br>
    <input type="file" id="image" name="newimage"><br>

    <label class="heading" for="price">Price:</label><br>
    <input type="number" size="70px" id="price" name="price" required value="<?php echo $row['price'] ?>" step="any"><br>

    <label class="heading">Station:</label><br>
    <input type="radio" id="drinks" name="station" value="STN1" <?php if ($row['stationID'] == 'STN1'){ echo 'checked'; }?>>
    <label for="drinks">Drinks</label><br>
    <input type="radio" id="hot" name="station" value="STN2" <?php if ($row['stationID'] == 'STN2'){ echo 'checked'; } ?>>
    <label for="hot">Hot</label><br>
    <input type="radio" id="dessert" name="station" value="STN3" <?php if ($row['stationID'] == 'STN3'){ echo 'checked'; } ?>>
    <label for="dessert">Dessert</label><br>
    
    <label class="heading">Category:</label><br>
    <input type="radio" id="appe" name="category" value="CAT01" <?php if ($row['categoryID'] == 'CAT01'){ echo 'checked'; } ?>>
    <label for="appe">Appetizers</label><br>
    <input type="radio" id="bf" name="category" value="CAT02" <?php if ($row['categoryID'] == 'CAT02'){ echo 'checked'; } ?>>
    <label for="bf">Breakfast Menu</label><br>
    <input type="radio" id="lunch" name="category" value="CAT03" <?php if ($row['categoryID'] == 'CAT03'){ echo 'checked'; } ?>>
    <label for="lunch">Lunch Menu</label><br>
    <input type="radio" id="tea" name="category" value="CAT04" <?php if ($row['categoryID'] == 'CAT04'){ echo 'checked'; } ?>>
    <label for="tea">Teatime</label><br>
    <input type="radio" id="drink" name="category" value="CAT05" <?php if ($row['categoryID'] == 'CAT05'){ echo 'checked'; } ?>>
    <label for="drink">Drinks</label><br>
    <input type="radio" id="dessert" name="category" value="CAT06" <?php if ($row['categoryID'] == 'CAT06'){ echo 'checked'; } ?>>
    <label for="dessert">Desserts</label><br>
    
    <label class="heading" for="prep">Preparation Time (minutes):</label><br>
    <input type="number" size="70px" id="prep" name="prep" required value="<?php echo $row['prepTime']; ?>"><br>
    
    <div class="avail" class="heading">
        Availability:
        <label class="switch">
            <input type="checkbox" id="availability" name="availability" <?php echo ($row['isAvailable'] == 1) ? 'checked' : ''; ?>>
            <span class="slider round" onclick="setAvail()"></span><br><br>
        </label><br><br>
    </div>

    <input type="hidden" name="availInt" id="availInt" value="<?php echo $row['isAvailable']; ?>">

    <input class="input-edit" id="editButton" type="submit" name="action" value="Edit"/>
    <input class="input-delete" id="deleteButton" type="submit" name="action" value="Delete"/>
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
