<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


// Generate OTP
$otp = mt_rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['email']=$_POST["email"];
$_SESSION['regno']=$_POST["regno"];
$_SESSION['password']=$_POST["password"];
$_SESSION['name']=$_POST['name'];

// Set up PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tyaginiharika113@gmail.com';   
    $mail->Password   = 'bchwulxrnzfrxrev';
    $mail->SMTPSecure = 'tls';            // Enable TLS encryption
    $mail->Port       = 587;              // TCP port to connect to

    // Sender and recipient
    $mail->setFrom('tyaginiharika113@gmail.com', 'Serviz');
    $mail->addAddress($_POST['email']); // Recipient

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'OTP Verification';
    $mail->Body    = 'Your OTP for verification is: ' . $otp;

    // Send email
    $mail->send();
    echo 'An email with OTP has been sent to ' . $_POST['email'];
    

    // Redirect to verify_otp.php with OTP parameter in the URL
    header("Location: otpsubmitform.php?email=" . urlencode($_POST['email']));
    exit;
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
