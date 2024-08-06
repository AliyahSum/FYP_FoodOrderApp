<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $miID = $_POST['menuitemid'];
    $action = $_POST['action'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());
    
    $message = "ERROR:";
    $target_dir = "../../images/";

    if ($action == "Edit") {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $station = $_POST['station'];
        $category = $_POST['category'];
        $prep = $_POST['prep'];
        $isAvailable = $_POST['availInt'];
        $oldImage = $_POST['image'];
        $newImage = isset($_FILES['newimage']['name']) ? $_FILES['newimage']['name'] : null;

        $old_target_file = $target_dir . basename($oldImage);
        $new_target_file = $target_dir . basename($newImage);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($new_target_file, PATHINFO_EXTENSION));

        $query = "SELECT * FROM menu_item WHERE menuitemID = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $miID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if ($newImage) {
                if (file_exists($old_target_file)) {
                    unlink($old_target_file);
                }
                if ($_FILES["newimage"]["size"] > 500000) {
                    $message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $message = "Sorry, only JPG, JPEG, PNG files are allowed.";
                    $uploadOk = 0;
                }
            }

            if ($uploadOk == 0) {
                $message = "Sorry, your image file was not uploaded.";
            } else {
                if ($newImage && move_uploaded_file($_FILES["newimage"]["tmp_name"], $new_target_file)) {

                    $image = $newImage;
                } else {
                    $image = $oldImage;
                }
                $sqlUpdate = "UPDATE menu_item SET item_name = ?, item_description = ?, image = ?, price = ?, isAvailable = ?, stationID = ?, categoryID = ?, prepTime = ? WHERE menuitemID = ?";
                $stmt = $link->prepare($sqlUpdate);
                $stmt->bind_param("sssdissis", $name, $desc, $image, $price, $isAvailable, $station, $category, $prep, $miID);
                if ($stmt->execute()) {
                    $message = "Menu Item updated successfully.";
                } else {
                    $message .= " Error updating Menu Item: " . $stmt->error;
                }
            }
        }
        $stmt->close();
    } elseif ($action == "Delete") {
        $isDelete = 1;
        $sqlUpdate = "UPDATE menu_item SET isDelete = ? WHERE menuitemID = ?";
        $stmt = $link->prepare($sqlUpdate);
        $stmt->bind_param("is", $isDelete, $miID);
        if ($stmt->execute()) {
            $message = "Menu Item deleted successfully.";
        } else {
            $message .= " Error updating Menu Item: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Invalid request method. Check if any of the fields are empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Menu Item</title>
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
            h1 {
                text-align: center;
            }
            h5 {
                text-align: center;
            }
            .backnav {
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Update Menu Item</a>
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
                            <a class="btn btn-outline-light backnav" onclick="history.back()">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="../../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1>Updating Menu Item...</h1>
        <h5><?php echo $message; ?></h5>
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
</script>
