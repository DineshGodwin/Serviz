<?php ob_start(); 
session_start();
include 'header.php';
// Check if user is already logged in, redirect to dashboard if yes
/*if(isset($_SESSION['regno'])) {
    header("Location: student_dash.php");
    exit();
}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
form{
            margin-top: 11px;  
            align-items: center;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: offwhite;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-message {
            color: red;
            margin-top: 5px;
            font-size:12px;
        }

        h4 {
            color: #324897;
            text-align: center;
            align-items: center;
        }

        a {
            font-size: 13px;
            text-align: center;
            display: block;
            margin-top: 8px;
            margin-bottom:0px !important;

        }

        .login-container {
            background-color: white; /* Semi-transparent blue */ /* Light blue with opacity */
    padding: 20px;
    height: 500px;
    width: 80%;
    max-width: 400px;
    border-radius: 20px; /* Adjust border-radius to make the border curved */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}



        .form-group {
            margin-bottom: 20px;
            align-items: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #324897;
            font-size: small;
        }
        input {
    width: 100%;
    padding: 8px;
    border: none; /* Remove the border */
    border-radius: 20px; /* Adjust border-radius to make the border curved */
    box-sizing: border-box;
    background-color: #f2f2f2; /* Optional: Add a background color */
}
img {
            width: 240px;
            height: 100px;
            margin-top: 10px;
            margin-left:35px;
        }

        .b1 {
            padding: 10px;
            cursor: pointer;
            background-color: #324897;
            color: #fff;
            border-radius: 4px;
            border: none;
            width: 30%;
            margin-bottom: 20px;
            margin-top: 30px;
            margin-left: 50px;
        }

        .submit{
            padding: 10px;
            cursor: pointer;
            background-color: #324897;
            color: #fff;
            border-radius: 4px;
            border: none;
            width: 30%;
            text-align: center;
            margin-left: 125px;
            margin-bottom:5px;
            margin-top:15px;
        }
        @media screen and (max-width: 600px) {
            .login-container {
                width: 90%;
            }

            .b1,
            .submit {
                width: 40%;
                margin-left: 30px;
            }
        }
        
             @media screen and (min-width: 300px) and (max-width:601px){
            .submit{
                margin-left:90px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
<img src="images/logo.jpg" alt="" class="img-fluid">
    <h4>Student Login</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="registerNo">Register Number:</label>
            <input type="text" id="registerNo" name="registerNo" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
            <button type="submit" class="submit">Submit</button>

        
        <a href="spassresetpage.php">Forgot password? Click here</a>
        <a href="studentsignup.php">Don't have an account? Sign up now!</a>
    </form>
</div>

<?php

include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registerNo = $_POST["registerNo"];
    $password = $_POST["password"];

    // Hash the entered password
    

    $sql = "SELECT * FROM student WHERE regno = '$registerNo'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Retrieve the hashed password from the database
        $stored_password= $row['password'];

        // Compare the hashed passwords
if ($password === $stored_password) {            
            $_SESSION['slt'] = $row['slt'];
            $_SESSION['regno'] = $row['regno'];

            if ($row['slt'] === NULL) {
                header("Location: home.php");
            } else {
                header("Location: student_dash.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid Register Number or Password.');</script>";
            header("refresh:0.1;url=studentlogin.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid Register Number or Password.');</script>";
        header("refresh:0.1;url=studentlogin.php");
        exit();
    }
}

mysqli_close($conn);
include 'footer.php';
?>



</body>
</html>
