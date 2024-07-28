<?php
session_start();

if (!isset($_SESSION['staff_id'])) {
    echo "
    <script>
    alert('Unauthorized access!');
    document.location.href = '/FYP_FoodOrderApp/index.php';
    </script>
    ";
    exit();
}

$staff_id = $_SESSION['staff_id'];

$host = "localhost";
$user = "root";
$password = "";
$db = "mydb";

$link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

function validatePassword($password) {
    return preg_match('/[A-Z]/', $password) && // At least one uppercase letter
           preg_match('/[a-z]/', $password) && // At least one lowercase letter
           preg_match('/[0-9]/', $password) && // At least one digit
           preg_match('/[!@#$%^&*()_\-+=\[\]{};:"|,.<>\/?~`]/', $password); // At least one special character
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "
        <script>
        alert('Passwords do not match');
        document.location.href = 'Login/resetPassword.php';
        </script>
        ";
        exit();
    }

    if (!validatePassword($new_password)) {
        echo "
        <script>
        alert('Password does not meet the complexity requirements');
        document.location.href = 'Login/resetPassword.php';
        </script>
        ";
        exit();
    }

    $hashed_password = sha1($new_password);

    $updateQuery = "UPDATE staff SET password = '$hashed_password' WHERE staffID = '$staff_id'";
    mysqli_query($link, $updateQuery) or die(mysqli_error($link));

    unset($_SESSION['verification_code']);
    unset($_SESSION['staff_id']);

    echo "
    <script>
    alert('Password reset successfully');
    document.location.href = '/FYP_FoodOrderApp/index.php';
    </script>
    ";
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }
    .card {
        max-width: 400px;
        width: 100%;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<body>
    <div class="card">
        <h2>Reset Password</h2>
        <form method="post" action="/FYP_FoodOrderApp/Login/resetPassword.php" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }

            if (!validatePassword(password)) {
                alert('Password does not meet the complexity requirements');
                return false;
            }

            return true;
        }

        function validatePassword(password) {
            const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()_\-+=\[\]{};:"|,.<>\/?~`]).{8,}$/;
            return regex.test(password);
        }
    </script>
</body>
</html>
