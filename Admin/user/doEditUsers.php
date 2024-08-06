<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staffID = $_POST['staffid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cfmpassword = $_POST['cfmpassword'];
    $isAvailable = $_POST['availInt'];
    $action = $_POST['action'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

    $message = "ERROR:";

    function validatePassword($password) {
        return preg_match('/[A-Z]/', $password) && 
                preg_match('/[a-z]/', $password) && 
                preg_match('/[0-9]/', $password) && 
                preg_match('/[!@#$%^&*()_\-+=\[\]{};:"|,.<>\/?~`]/', $password);
    }

    if ($action == "Edit") {
        if ($pass !== $cfmpassword) {
            $message .= " Passwords do not match.";
        }
        elseif (strlen($pass) < 8) {
            $message .= " Password must be 8 characters or more.";
        }
        elseif (!validatePassword($pass)) {
            $message .= " Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
        elseif (strtotime($dob) > time()) {
            $message .= " Date of Birth cannot be a future date.";
        } else {
            $query = "SELECT * FROM staff WHERE staffID = ?";
            $stmt = $link->prepare($query);
            $stmt->bind_param("s", $staffID);
            $stmt->execute();
            $result = $stmt->get_result();
            $hashed_password = sha1($pass);

            if ($result->num_rows > 0) {
                $sqlUpdate = "UPDATE staff SET password = ?, first_name = ?, last_name = ?, DOB = ?, email = ?, isAvailable = ? WHERE staffID = ?";
                $stmt = $link->prepare($sqlUpdate);
                $stmt->bind_param("sssssis", $hashed_password, $fname, $lname, $dob, $email, $isAvailable, $staffID);

                if ($stmt->execute()) {
                    $message = "User updated successfully.";
                } else {
                    $message .= " Error updating user: " . $stmt->error;
                }
            } else {
                $message .= " Staff ID does not exist.";
            }
            $stmt->close();
        }
    } elseif ($action == "Delete") {
        $isDelete = 1;
        $sqlUpdate = "UPDATE staff SET isDelete = ? WHERE staffID = ?";
        $stmt = $link->prepare($sqlUpdate);
        $stmt->bind_param("is", $isDelete, $staffID);
        if ($stmt->execute()) {
            $message = "User deleted successfully.";
        } else {
            $message .= " Error updating Users: " . $stmt->error;
        }
        $stmt->close();
    }
    mysqli_close($link);
} else {
    $message = "Invalid request method. Check if any of the fields are empty.";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Users</title>
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
                <a class="navbar-brand" href="#">Update Users</a>
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
        
        <h1>Updating Users...</h1>
        <h5>
            <?php echo $message; ?>
        </h5>
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
