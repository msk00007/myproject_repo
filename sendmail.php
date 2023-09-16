<?php
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src//SMTP.php';
require './vendor/phpmailer/phpmailer/src//Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$to_email = "machha394@gmail.com";
$subject = "Simple Email Test via PHPMailer";
$body = "Hi, This is a test email sent by PHPMailer.";
$from_email ="machha394@gmail.com";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['code'])) {
    $to_email = $_POST['email'];
    $code = $_POST['code'];


    $subject = "Email Verification Code";
    $body = $code;
    $from_email = "machha394@gmail.com"; // Replace with your actual email address
}
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'machha394@gmail.com'; // Your SMTP username
    $mail->Password = 'fhkdeyeuvjyjpapv'; // Your SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption
    $mail->Port = 587; // SMTP port

    $mail->setFrom($from_email);
    $mail->addAddress($to_email);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    echo "Email successfully sent to $to_email...";
    $email_status = true;
} catch (Exception $e) {
    $email_status = false;
    echo "Email sending failed. Error: {$mail->ErrorInfo}";
}
?>
