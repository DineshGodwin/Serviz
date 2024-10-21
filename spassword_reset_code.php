<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

include('db.php');
session_start();
$page_title = "Change password";

function send_password_reset($get_name,$get_email,$token){
    $mail = new PHPMailer(true);

    //$mail->SMTPDebug = 2;
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->isSMTP();  
    $mail->SMTPAuth   = true;  

    $mail->Host       = 'smtp.gmail.com';
    $mail->Username   = 'tyaginiharika113@gmail.com';                     
    $mail->Password   = 'bchwulxrnzfrxrev';

    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    $mail->setFrom('tyaginiharika113@gmail.com', $get_name);
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject = "Reset Password Notification";

    $email_template = "
    <h2>Hello $get_name,</h2>
    <h5>We have recieved a password reset request from your account.</h5>
    <br/><br/>
    <a href='http://localhost/teacher/spassword_change.php?token=$token&email=$get_email'>Click me</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if(isset($_POST['password_reset_link'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT name,email FROM student WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($check_email_run) > 0){
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['name'];
        $get_email = $row['email'];

        $update_token = "UPDATE student SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);
        if($update_token_run){
            send_password_reset($get_name,$get_email,$token);
            $_SESSION['status'] = "Reset link has been sent. Please check your mail...";
            header('Location: spassresetpage.php');
            exit(0);

        }
        else{
            $_SESSION['status'] = "Something went wrong. #1";
            header('Location: spassresetpage.php');
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "No email found";
        header('Location: spassresetpage.php');
        exit(0);
    }
}

if(isset($_POST['password_update'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['password_token']);

    if(!empty($token)){
        if(!empty($email) && !empty($new_password) && !empty($confirm_password)){
            //Checking the validity of the token
            $check_token = "SELECT verify_token FROM student WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);
            if(mysqli_num_rows($check_token_run) > 0){

                if($new_password==$confirm_password){
                    $new_password_hash= password_hash($new_password, PASSWORD_DEFAULT);
                    $update_password = "UPDATE student SET password ='$new_password_hash' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($conn, $update_password);

                    if($update_password_run){

                        $new_token = md5(rand())."guru";
                        $update_to_new_token = "UPDATE student SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                        $update_to_new_token_run = mysqli_query($conn, $update_password);

                        $_SESSION['status'] = "Password Changed Successfully...";
                        header("Location: studentlogin.php ");
                        exit(0);
                    }
                    else{
                        $_SESSION['status'] = "Something went wrong. #2";
                        header("Location: spassword_change.php?token='$token'&email='$email'");
                        exit(0);
                    }
                }
                else{
                    $_SESSION['status'] = "Password and confirm password doesn't match...";
                    header("Location: spassword_change.php?token='$token'&email='$email'");
                    exit(0);
                }
            }
            else{
                $_SESSION['status'] = "Invalid token...";
                header("Location: spassword_change.php?token='$token'&email='$email'");
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "All fields are mandatory...";
            header("Location: spassword_change.php?token='$token'&email='$email'");
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "No token found...";
        header('Location: spassword_change.php');
        exit(0);
    }
    }