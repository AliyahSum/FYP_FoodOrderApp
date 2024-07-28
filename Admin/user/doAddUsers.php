<?php
$staffID = $_POST['staffid'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$pass = $_POST['password'];
$cfmpassword = $_POST['cfmpassword'];
$adminRole = $_POST['adminInt'];

$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$message = "ERROR:";

// Password validation function
function validatePassword($password) {
    return preg_match('/[A-Z]/', $password) && // At least one uppercase letter
            preg_match('/[a-z]/', $password) && // At least one lowercase letter
            preg_match('/[0-9]/', $password) && // At least one digit
            preg_match('/[!@#$%^&*()_\-+=\[\]{};:"|,.<>\/?~`]/', $password);      // At least one special character
}

// Check if passwords match
if ($pass !== $cfmpassword) {
    $message .= " Passwords do not match.";
}

// Validate password length
elseif (strlen($pass) < 8) {
    $message .= " Password must be 8 characters or more.";
}

// Validate password complexity
elseif (!validatePassword($pass)) {
    $message .= " Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
}
// Validate date of birth
elseif (strtotime($dob) > time()) {
    $message .= " Date of Birth cannot be a future date.";
} else {
    // Check if staff ID is unique
    $query = "SELECT * FROM staff WHERE staffID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= " Staff ID must be unique.";
    } else {
        // Hash the password using SHA-1
        $hashed_password = sha1($pass);

        // Insert new staff member
        $sqlInsert = "INSERT INTO staff (staffID, password, first_name, last_name, DOB, email, admin_role, isAvailable) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $link->prepare($sqlInsert);
        $stmt->bind_param("sssssss", $staffID, $hashed_password, $fname, $lname, $dob, $email, $adminRole);

        if ($stmt->execute()) {
            $message = "User added successfully.";
        } else {
            $message .= " Error adding user: " . $stmt->error;
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
        <title>Add Users</title>
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
                <a class="navbar-brand" href="#">Add Users</a>
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

        <h1>Adding New Staff Account...</h1>
        <h5>
            <?php echo $message; ?>
        </h5>
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add event listener to the logout button
    document.getElementById('logoutButton').addEventListener('click', function (event) {
        // Prevent default action
        event.preventDefault();
        // Show confirmation dialog
        if (confirm('Are you sure you want to logout?')) {
            // If confirmed, proceed with the logout
            window.location.href = '/FYP_FoodOrderApp/index.php';
        }
    });
</script>
