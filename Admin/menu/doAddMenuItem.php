<?php

$miID = $_POST['miID'];
$name = $_POST['name'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$station = $_POST['station'];
$category = $_POST['category'];
$prep = $_POST['prep'];

$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$message = "ERROR:";

// Handle the image file upload
$target_dir = "../../images/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$uploadOk = 1;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $message .= " File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $message .= " Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["image"]["size"] > 500000) {
    $message .= " Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $message .= " Sorry, only JPG, JPEG & PNG files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $message .= " Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = basename($_FILES["image"]["name"]); // Save the file name in the database
    } else {
        $message .= " Sorry, there was an error uploading your file.";
        $uploadOk = 0;
    }
}

if ($uploadOk == 1) {
    $query = "SELECT * FROM menu_item WHERE menuitemID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $miID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= " Menu Item ID must be unique.";
    } else {
        // Insert new menu item
        $sqlInsert = "INSERT INTO menu_item (menuitemID, item_name, item_description, image, price, isAvailable, stationID, categoryID, prepTime) VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?)";
        $stmt = $link->prepare($sqlInsert);
        $stmt->bind_param("ssssssss", $miID, $name, $desc, $image, $price, $station, $category, $prep);
        
        if ($stmt->execute()) {
            $message = "Menu Item added successfully.";
        } else {
            $message .= " Error adding menu item: " . $stmt->error;
        }
    }

    $stmt->close();
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
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
        
        h5{
            text-align: center;
        }
        
        .backnav{
            margin-right: 10px;
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
                        <a class="btn btn-outline-light backnav" onclick="history.back()">Back</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light logOutNav" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Adding New Menu Item...</h1>
    <h5>
        <?php echo $message; ?>
    </h5>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
</script>
