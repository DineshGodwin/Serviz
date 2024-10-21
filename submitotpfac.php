<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('displayconnection._errors', 1);
include 'db.php';


// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['otp'], $_POST['email'])) {
    // Retrieve OTP from session
    $otp = isset($_SESSION['otp']) ? $_SESSION['otp'] : '';

    // Validate OTP entered by user
    $enteredOTP = $_POST['checkotp'];
    if ($enteredOTP == $otp) {
        echo "OTP verified successfully.";
        $email=$_SESSION['email'];
       
        $password=password_hash($_SESSION['password'], PASSWORD_DEFAULT);
        $name=$_SESSION['name'];

        $stmt = $conn->prepare("INSERT INTO faculty (email, password,name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password,$name);

        // Execute the statement
        if ($stmt->execute()) {
           // echo "User registered successfully.";
            header("Location: http://servizcu.000webhostapp.com/facultylogin.php");
            exit(); // Stop further execution
            // You can redirect the user to a success page or perform further actions here
        } else {
            echo "Error: " . $conn->error;
            // You can redirect the user back to the registration form or display an error message
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Incorrect OTP. Please try again.";
        // You can redirect the user back to the OTP input form or display an error message
    }
} else {
    echo "Form not submitted or OTP not provided.";
}
?>

        
        

        

