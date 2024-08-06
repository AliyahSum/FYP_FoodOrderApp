<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpMailer/src/Exception.php';
require '../phpMailer/src/PHPMailer.php';
require '../phpMailer/src/SMTP.php';

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

$query = "SELECT * FROM staff";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

$arrContent = [];

while ($row = mysqli_fetch_assoc($result)) {
    $arrContent[] = $row;
}

mysqli_close($link);

$valid = 0;

for ($i = 0; $i < count($arrContent); $i++) {
    $staffID = $arrContent[$i]['staffID'];
    $fullName = $arrContent[$i]['first_name'] . " " . $arrContent[$i]['last_name'];
    $email = $arrContent[$i]['email'];     

    if ($staffID == $_POST['staffid'] && strtolower($fullName) == strtolower($_POST['fullname']) && $email == $_POST['email']) {
        $valid = 1;
    }
}

if ($valid == 1) {
    $randNum = rand(100000, 999999);
    $_SESSION['verification_code'] = $randNum;
    $_SESSION['staff_id'] = $_POST['staffid'];

    if (isset($_POST["send"])) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'foodorderappdontreply@gmail.com';
            $mail->Password = 'jyivrntdeeyfwnwz';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('foodorderappdontreply@gmail.com');
            $mail->addAddress($_POST['email']);

            $mail->isHTML(true);
            $mail->Subject = "Verification Code";
            $mail->Body = "Your verification code is: $randNum";

            $mail->send();

            echo "
            <script>
            alert('Verification code sent successfully');
            document.location.href = '../Login/verifyCode.php';
            </script>
            ";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
} else {
    echo "
    <script>
    alert('Something went wrong! Check your staff id, full name and email again.');
    document.location.href = '../Login/forgotPassword.php';
    </script>
    ";
}
?>
