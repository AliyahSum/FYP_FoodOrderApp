<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['verification_code'];
    $session_code = $_SESSION['verification_code'];

    if ($entered_code == $session_code) {
        echo "
        <script>
        alert('Verification successful. Please reset your password.');
        document.location.href = '/FYP_FoodOrderApp/Login/resetPassword.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Invalid verification code. Please try again.');
        document.location.href = '/FYP_FoodOrderApp/Login/verifyCode.php';
        </script>
        ";
    }
}
?>
